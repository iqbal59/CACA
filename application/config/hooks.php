<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );

$hook['post_controller_constructor'][] = array(
	'class'    => 'Remote_Check',
	'function' => 'index',
	'filename' => 'App_Config.php',
	'filepath' => 'hooks'
);
