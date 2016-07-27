<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
	session_start();
	ob_start();
	$debug =false;
	require_once "facebook-php-sdk/src/Facebook/autoload.php";
	
	$fb = new Facebook\Facebook([
	  'app_id' => '1727308334163655',
	  //'1727308334163655',
	  'app_secret' => '372b788755867660e65f0562815f03e5',
	  'default_graph_version' => 'v2.5',
	  ]);
	  
	$helper = $fb->getRedirectLoginHelper();
	if(!isset($DO_NOT_CLOSE_SESSION))
		session_write_close();
