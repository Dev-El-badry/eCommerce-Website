<?php

	function lang( $phrase ) {

		//Home PAge
		static $lang = [
			'MESSAGE' => 'Welcome',
			'ADMIN' => 'ADMINSTRATOR'
		];

		return $lang[$phrase];
	}