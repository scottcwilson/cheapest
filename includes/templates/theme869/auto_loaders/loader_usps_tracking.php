<?php

$loaders[] = array(
    'conditions' => array(
        'pages' => array('*') 
    ),
    'jscript_files' => array(
        '//code.jquery.com/jquery-1.11.3.min.js' => 1,
        'jquery/jquery_uspstracking.js' => 3, // this is set to 3 incase a jquery migrate file is needed
    ),
    'css_files' => array(
        'usps_tracking_sidebox.css' => 1,)
);

$loaders[] = array(
    'conditions' => array(
        'pages' => array('usps_tracking') 
    ),
    'css_files' => array(
        'usps_tracking.css' => 1,)
);