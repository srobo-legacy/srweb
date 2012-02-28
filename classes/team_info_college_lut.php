<?php

$_ARG = array('URL' => 'http://www.ac-grenoble.fr/argouges/',		'name' => 'Lycée Argouges');
$_BWS = array('URL' => 'http://www.bws.wilts.sch.uk/',				'name' => 'Bishop Wordsworth School');
$_BGR = array('URL' => 'http://www.bristolgrammarschool.co.uk/',	'name' => 'Bristol Grammar School');
$_BRK = array('URL' => 'http://www.brock.ac.uk/',					'name' => 'Brockenhurst College');
$_BSG = array('URL' => 'http://www.bsg.bournemouth.sch.uk/',		'name' => 'Bournemouth School for Girls');
$_CLF = array('URL' => 'http://www.cliftonhigh.bristol.sch.uk/',	'name' => 'Clifton High School');
$_EMM = array('URL' => 'http://www.ac-grenoble.fr/mounier/',		'name' => 'Lycée Emmanuel Mounier');
$_GRS = array('URL' => 'http://www.greshams.com/',					'name' => 'Gresham\'s');
$_GMR = array('URL' => 'http://www.farn-ct.ac.uk/',					'name' => 'Farnborough');
$_HRS = array('URL' => 'http://www.hrsfc.ac.uk/',					'name' => 'Hills Road Sixth Form College');
$_HZW = array('URL' => 'http://www.hazelwick.w-sussex.sch.uk/',		'name' => 'Hazelwick Comprehensive School');
$_MFG = array('URL' => 'http://www.themfg.co.uk/',					'name' => 'Mirfield Free Grammar');
$_PSC = array('URL' => 'http://www.psc.ac.uk/',						'name' => 'Peter Symonds');
$_QEH = array('URL' => 'http://www.qehbristol.co.uk/',				'name' => 'Queen Elizabeth\'s Hospital School Bristol');
$_QMC = array('URL' => 'http://www.qmc.ac.uk/',						'name' => 'Queen Mary\'s College');
$_RMS = array('URL' => 'http://www.redmaids.bristol.sch.uk/',		'name' => 'Red Maids Bristol');
$_SEN = array('URL' => 'http://www.shsb.org.uk/',					'name' => 'Southend School');
$_STA = array('URL' => 'http://www.st-annes.southampton.sch.uk/',	'name' => 'St. Anne\'s');
$_TTN = array('URL' => 'http://www.tauntons.ac.uk/',				'name' => 'Tauntons College');

$_MUC = array('name' => 'Munich');

$team_info_college_lut = array(
	'SRZ' => array('name' => "School of SR", 'URL' => "http://www.bbc.co.uk/"),
);

$team_ids_lut = array(
	 1 => 'ARG',
	 2 => 'BWS',
	 3 => 'BWS2',
	 4 => 'BGR',
	 5 => 'BRK',
	 6 => 'BSG',
	 7 => 'CLF',
	 8 => 'CLF2',
	 9 => 'EMM',
	10 => 'GRS',
	11 => 'GMR',
	12 => 'HRS',
	13 => 'HZW',
	14 => 'MFG',
	15 => 'MUC',
	16 => 'PSC',
	17 => 'PSC2',
	18 => 'QEH',
	19 => 'QMC',
	21 => 'SEN',
	22 => 'SEN2',
	24 => 'TTN',
);

foreach ($team_ids_lut as $nid => $tla) {
	$college_info_name = '_'.substr($tla, 0, 3);
	$college_info = $$college_info_name;
	$team_info_college_lut[$nid] = $team_info_college_lut[$tla] = $college_info;
}

?>
