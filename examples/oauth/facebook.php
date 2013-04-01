<?php

require('../../framework.php');
require('../../oauth2.php');

session_name('oauth2_example');
session_start();

$FacebookAuth = new Facebook_OAuth2 (FACEBOOK_APP_ID, FACEBOOK_APP_SECRET, FACEBOOK_APP_CALLBACK);

if (!isset($_SESSION['fb_access_token'])) {
	
	if (!count($_GET)) {
		
		$_SESSION['state'] = sha(uniqid(rand(), true));
		
		$FacebookAuth->state = $_SESSION['state'];
		
		$FacebookAuth->authenticate();
		
	} else {
		
		if (isset($_SESSION['state'], $_GET['state']) && $_SESSION['state'] == $_GET['state']) {
			
			unset($_SESSION['state']);
			
			$FacebookAuth->callback(function ($return) {
				
				$_SESSION['fb_access_token'] = OAuth2::parseToken($return);
				
				header('Location: /examples/oauth/facebook.php'); exit;
				
			});
			
		} else {
			
			header('Location: /examples/oauth/error.php'); exit;
			
		}
		
	}
	
} else {
	
	$results = json_decode($FacebookAuth->request('https://graph.facebook.com/me?access_token=' . $_SESSION['fb_access_token']));
	
	print_array($results);
	
}
