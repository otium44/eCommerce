<?php

function lang($phrase) {

	static $lang = array(

		'MESSAGE' => 'Welcome', 
		'ADMIN' => 'administrator'
	);
	return $lang[$phrase];
}

