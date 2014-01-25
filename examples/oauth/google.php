<?php

require('../../framework.php');
require('../../oauth2.php');

require('config.php');

session_name('oauth2_example');
session_start();

$GoogleAuth = new GooglePlus_OAuth2 (GOOGLE_APP_ID, GOOGLE_APP_SECRET, GOOGLE_APP_CALLBACK);

if (!isset($_SESSION['gp_access_token'])) {

	if (!count($_GET)) {

		$_SESSION['state'] = sha(uniqid(rand(), true));

		$GoogleAuth->state = $_SESSION['state'];

		$GoogleAuth->authenticate();

	} else {

		if (isset($_SESSION['state'], $_GET['state']) && $_SESSION['state'] == $_GET['state']) {

			unset($_SESSION['state']);

			$GoogleAuth->callback(function ($return) {

				$_SESSION['gp_access_token'] = OAuth2::parseToken($return);

				header('Location: /examples/oauth/google.php'); exit;

			});

		} else {

			header('Location: /examples/oauth/error.php'); exit;

		}

	}

} else {

	$results = json_decode($GoogleAuth->request('https://www.googleapis.com/plus/v1/people/me/?access_token=' . $_SESSION['gp_access_token']));

	print_array($results);

}
