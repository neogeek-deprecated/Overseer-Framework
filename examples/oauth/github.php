<?php

require('../../framework.php');
require('../../oauth2.php');

session_name('oauth2_example');
session_start();

$GitHubAuth = new GitHub_OAuth2 (GITHUB_APP_ID, GITHUB_APP_SECRET, GITHUB_APP_CALLBACK);

if (!isset($_SESSION['git_access_token'])) {
	
	if (!count($_GET)) {
		
		$_SESSION['state'] = sha(uniqid(rand(), true));
		
		$GitHubAuth->state = $_SESSION['state'];
		
		$GitHubAuth->authenticate();
		
	} else {
		
		if (isset($_SESSION['state'], $_GET['state']) && $_SESSION['state'] == $_GET['state']) {
			
			unset($_SESSION['state']);
			
			$GitHubAuth->callback(function ($return) {
				
				$_SESSION['git_access_token'] = OAuth2::parseToken($return);
				
				header('Location: /examples/oauth/github.php'); exit;
				
			});
			
		} else {
			
			header('Location: /examples/oauth/error.php'); exit;
			
		}
		
	}
	
} else {
	
	$results = json_decode($GitHubAuth->request('https://api.github.com/user?access_token=' . $_SESSION['git_access_token']));
	
	print_array($results);
	
}
