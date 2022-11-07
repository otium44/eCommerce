<?php

function lang($phrase) {

	static $lang = array(
		// Navbar Links
		'Category' 		=> 'Categories', 
		'Main' 			=> 'Home',
		'ITEMS' 		=> 'Items',
		'MEMBERS' 		=> 'Members',
		'COMMENTS'	=> 'Comments',
		'STATISTICS'	=> 'Statistics',
		'LOGS' 			=> 'Logs'
	);
	return $lang[$phrase];
}

