<?php

    function lang( $phrase ) {
        
        static $lang = array(
            'MESSAGE' => 'اهلا بيك',
            'ADMIN' => 'الرئيسية'
        );
        
        return $lang[$phrase];
    }