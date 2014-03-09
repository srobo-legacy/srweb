<?php
// Special page to merge together the information in the current teams.json with the team status info.

require_once('config.inc.php');
require_once('classes/team_info.php');

$team_satutes = get_team_info();

$combined_teams_info = array();


foreach ($team_info_college_lut as $tla => $info) {
    if ($tla == 'SRZ') {
        continue;
    }
    if (isset($team_satutes[$tla])) {
        $info = $team_satutes[$tla];
    } else {
        // no status -- just convert the layout of the information
        $info = array('college' => $info);
    }
    $combined_teams_info[$tla] = $info;
}

header('Content-Type: text/json');
echo json_encode($combined_teams_info);
