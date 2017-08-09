<?php
/*
    Voter Visibility SMF Mod - Enhances polling to allow voters to be displayed for those needing to record the results of polls.
    Copyright (C) 2012-2017  Ray Cardillo (Cardillo's Creations)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Vote Visibility Modification
 *
 * @package SMF
 * @author Ray Cardillo https://CardillosCreations.com
 * @copyright 2012-2017 Cardillo's Creations
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPLv3
 *
 * @version 2.2
 */

if (!defined('SMF'))
	die('Hacking attempt...');

// Determine what voters to show based on visibility
function VoteLog()
{
	global $settings, $txt, $user_info, $context, $topic, $board, $smcFunc, $sourcedir, $scripturl;

	if (empty($topic))
		fatal_lang_error('no_access');

	loadLanguage('Post');
	loadTemplate('VoteLog');

	// the table needs some special sauce
	$context['html_headers'] .= '
	<link rel="stylesheet" type="text/css" href="' . $settings['default_theme_url'] . '/css/votelog.css" />
	<script language="javascript" type="text/javascript">
	if (typeof jQuery == "undefined" || jQuery.fn.jquery != "1.11.3") {
		// if we do not already have jQuery 1.11.3 loaded then load using Google CDN (better for caching, less bandwidth, etc).
		document.write(unescape("%3Cscript language=\'javascript\' type=\'text/javascript\' src=\'http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js\'%3E%3C/script%3E"));
	}
	</script>
	<script language="javascript" type="text/javascript">
	if (typeof jQuery == "undefined" || jQuery.fn.jquery != "1.11.3") {
		// if we still do not have jQuery 1.11.3 loaded then load the locally deployed version as a backup.
		document.write(unescape("%3Cscript language=\'javascript\' type=\'text/javascript\' src=\'' . $settings['default_theme_url'] . '/scripts/jquery-1.11.3.min.js\'%3E%3C/script%3E"));
	}
	</script>
	<script language="javascript" type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/jquery-tablesorter-2.0.5.min.js"></script>';
	
	// Check if a poll currently exists on this topic, and get the id and some other basic info
	$request = $smcFunc['db_query']('', '
		SELECT
			p.id_poll, p.question, p.hide_results, p.vote_visibility, p.id_member, m.subject
		FROM
			{db_prefix}topics AS t
			INNER JOIN {db_prefix}messages AS m ON (m.id_msg = t.id_first_msg)
			LEFT JOIN {db_prefix}polls AS p ON (p.id_poll = t.id_poll)
		WHERE
			t.id_topic = {int:current_topic}
		LIMIT 1',
		array(
			'current_topic' => $topic,
		)
	);
	
	// Make sure the topic exists (the query returned something).
	if ($smcFunc['db_num_rows']($request) == 0)
		fatal_lang_error('no_board');

	// Get the poll information.
	$pollinfo = $smcFunc['db_fetch_assoc']($request);
	$smcFunc['db_free_result']($request);

	// Make sure the poll exists.
	if (empty($pollinfo['id_poll']))
		fatal_lang_error('poll_not_found');

	// This is almost the same as the code in Display.php because you should not see voters if you cannot see the poll
	// You're allowed to view the results if:
	// 1. you're just a super-nice-guy, or
	// 2. anyone can see them (hide_results == 0), or
	// 3. you can see them after you voted (hide_results == 1), or
	// 4. you've waited long enough for the poll to expire. (whether hide_results is 1 or 2.)
	$context['allow_poll_view'] = allowedTo('moderate_board') || $pollinfo['hide_results'] == 0 || ($pollinfo['hide_results'] == 1 && $context['poll']['has_voted']) || $context['poll']['is_expired'];
	$context['poll']['show_results'] = $context['allow_poll_view'] && (isset($_REQUEST['viewresults']) || isset($_REQUEST['viewResults']));
	$context['poll']['question'] = $pollinfo['question'];
	$context['poll']['is_locked'] = !empty($pollinfo['voting_locked']);
	$context['poll']['vvis'] = $pollinfo['vote_visibility'];
	
	// verify we can at least view the poll
	isAllowedTo('poll_view');
	
	// admins and PUBLIC (2) visibility can always be displayed
	if ($pollinfo['vote_visibility'] != 2 && !allowedTo('admin_forum'))
	{
		// if it is marked PRIVATE (0), only admins can view voter log
		if ($pollinfo['vote_visibility'] == 0)
			fatal_lang_error('poll_vv_vlog_err_private');
			
		// if it is marked DISCREET (1), only admins and the creator can view the voter log
		if ($pollinfo['vote_visibility'] == 1 && $user_info['id'] != $pollinfo['id_member'])
			fatal_lang_error('poll_vv_vlog_err_discreet');
	}
	
	// NOTE: Update if SMF redesigns the way they track the poll results to recall deleted members in old polls (e.g., if they start storing the name like they do for posts).
	$request = $smcFunc['db_query']('', '
		SELECT
			p.timestamp, p.id_member, c.id_choice, c.label, p.comments,
			IFNULL(m.real_name, "' . $txt['poll_vv_vlog_guest_or_unknown'] . '") AS voter_name
		FROM
			{db_prefix}log_polls AS p
			INNER JOIN {db_prefix}poll_choices AS c ON (p.id_choice = c.id_choice AND p.id_poll = c.id_poll)
			LEFT JOIN {db_prefix}members AS m ON (p.id_member = m.id_member)
		WHERE
			p.id_poll = {int:current_poll}
		ORDER BY
			c.id_choice',
		array(
			'current_poll' => $pollinfo['id_poll'],
		)
	);
	
	// Fail if there are no results to display
	if ($smcFunc['db_num_rows']($request) == 0)
		fatal_lang_error('poll_vv_vlog_empty', false);

	// store the voter log and release the db results
	$rownum = 0;
	$context['votelog'] = array();
	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		censorText($row['label']);

		// add the voter log
		$context['votelog'][++$rownum] = array(
			'timestamp' => $row['timestamp'],
			'member_id' => $row['id_member'],
			'member_name' => $row['voter_name'],
			'choice_num' => $row['id_choice'],
			'choice_lbl' => $row['label'],
			'comments' => $row['comments']
		);
	}

	$smcFunc['db_free_result']($request);

	$context['page_title'] = $txt['poll_vv_vlog_title'];

	// Build the link tree.
	censorText($pollinfo['subject']);
	$context['linktree'][] = array(
		'url' => $scripturl . '?topic=' . $topic . '.0',
		'name' => $pollinfo['subject'],
	);
	$context['linktree'][] = array(
		'name' => $context['page_title'],
	);

	// Register this form in the session variables.
	checkSubmitOnce('register');
}

?>
