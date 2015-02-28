<?php

return array(

    'pdf' => array(
        'enabled' => TRUE,
        'binary'  => '/usr/local/bin/wkhtmltopdf',
        'options' => array(
	        'lowquality'  => FALSE,
            'image-dpi'   => 600,
            'orientation' => 'landscape',
            'page-size'   => 'letter',
        ),
    ),

    'image' => array(
        'enabled' => TRUE,
        'binary'  => '/usr/local/bin/wkhtmltoimage',
        'options' => array(),
    ),

);
