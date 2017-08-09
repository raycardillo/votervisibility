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

global $db_prefix, $smcFunc;

$smcFunc['db_add_column'](
	'{db_prefix}polls',
	array(
		'name'     => 'vote_visibility',
		'type'     => 'tinyint',
		'size'     => 1,
		'null'     => 'false',
		'default'  => 0,
		'unsigned' => 'true'
	)
);

$smcFunc['db_add_column'](
	'{db_prefix}polls',
	array(
		'name'     => 'allow_comments',
		'type'     => 'tinyint',
		'size'     => 1,
		'null'     => 'false',
		'default'  => 0,
		'unsigned' => 'true'
	)
);

$smcFunc['db_add_column'](
	'{db_prefix}poll_choices',
	array(
		'name'     => 'requires_comment',
		'type'     => 'tinyint',
		'size'     => 1,
		'null'     => 'false',
		'default'  => 0,
		'unsigned' => 'true'
	)
);

$smcFunc['db_add_column'](
	'{db_prefix}log_polls',
	array(
		'name'     => 'timestamp',
		'type'     => 'int',
		'size'     => 11,
		'null'     => 'true',
		'unsigned' => 'true'
	)
);

$smcFunc['db_add_column'](
	'{db_prefix}log_polls',
	array(
		'name'     => 'comments',
		'type'     => 'text',
		'null'     => 'true'
	)
);

?>
