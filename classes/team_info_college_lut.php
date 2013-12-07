<?php

$teams_json = 'resources/2014/teams.json';

$team_info_college_lut = json_decode(file_get_contents($teams_json), true);

if ($team_info_college_lut === null) {
	trigger_error("Failed to parse team LUT", E_USER_WARNING);
}
