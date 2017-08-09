<?php
/**
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

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl;
	
	// Start the main poll form.
	echo '
	<div id="vote_log">
		<div class="cat_bar">
			<h3 class="catbg">
				<span class="ie6_header floatleft">
					<img src="',
					$settings['images_url'], '/topic/',
					$context['poll']['is_locked'] ? 'normal_poll_locked' : 'normal_poll', '.gif" alt="" class="icon" />',
					$context['page_title'], '
				</span>			
			</h3>
		</div>
		<span class="upperframe"><span></span></span>
		<div class="roundframe">';
			
	if (!empty($context['poll_error']['messages']))
		echo '
			<div class="errorbox">
				<dl class="poll_error">
					<dt>
						', empty($context['poll_error']['messages']) ? '' : implode('<br />', $context['poll_error']['messages']), '
					</dt>
				</dl>
			</div>';
			
	// we need to be able to easily lookup which label and description to display when warning the user about visibility
	$vvisTxt = array(
		'poll_vv_opt_private',
		'poll_vv_opt_discreet',
		'poll_vv_opt_public'
	);

	echo '
			<em>', sprintf($txt['poll_vv_vlog_for'], $txt[$vvisTxt[$context['poll']['vvis']]]), '</em> &nbsp; <b>', $context['poll']['question'], '</b>
			<div class="votelog_top"><span></span></div>
			<table id="voteLogTable" class="votelog">
				<thead>
				<tr>
					<th style="text-align:left;   halign:left;   width:17em;">', $txt['poll_vv_vlog_th_tstamp'], '</th>
					<th style="text-align:left;   halign:left;   width:17em;">', $txt['poll_vv_vlog_th_member'], '</th>
					<th style="text-align:center; halign:center; width:5em;">', $txt['poll_vv_vlog_th_choicenum'], '</th>
					<th style="text-align:left;   halign:left;   width:auto;">', $txt['poll_vv_vlog_th_choicelbl'], ' &nbsp; / &nbsp; ', $txt['poll_vv_vlog_th_comment'], '</th>
				</tr>
				</thead>
				<tbody>';
				
		foreach ($context['votelog'] as $votelog) {
			$memberurl = $scripturl . '?action=profile;u=' . $votelog['member_id'];
			$commentpart = empty($votelog['comments'])?'':' <br /> <em>' . $votelog['comments'] . '</em>';
			echo'
				<tr>
					<td style="text-align:left;   halign:left;">', empty($votelog['timestamp'])?'<em>not available</em>':timeformat($votelog['timestamp'],false), '</td>
					<td style="text-align:left;   halign:left;"><a href="', $memberurl, '" title="', $txt['profile_of'], ' ', $votelog['member_name'], '">', $votelog['member_name'], '</a></td>
					<td style="text-align:center; halign:center;">', 1+$votelog['choice_num'], '</td>
					<td style="text-align:left;   halign:left;">
					<b>', $votelog['choice_lbl'], '</b>', $commentpart, '
					</td>
				</tr>';
		}
		
		echo '
				</tbody> 
			</table>
			<script language="javascript" type="text/javascript"><!-- // --><![CDATA[
				$(document).ready(function() { 
					$("#voteLogTable").tablesorter( {sortList: [[0,0], [2,0]]} ); 
				});
			// ]]></script>
			<div class="votelog_bot"><span></span></div>
		</div>
		<span class="lowerframe"><span></span></span>
	</div>	
	<br class="clear" />';
}

?>
