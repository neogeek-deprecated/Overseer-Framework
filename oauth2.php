<?php

# namespace NeoGeek\OverseerFramework;

/**
 * OAuth2
 * A simple PHP class for establishing OAuth 2.0 connections.
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2012, Neo Geek
 */

class OAuth2 {
	
	public $id;
	public $secret;
	public $callback;
	
	public $url_authorize;
	public $url_access_token;
	
	/**
	 * __construct
	 * Sets up a new OAuth2 object.
	 * @method object OAuth2 (string $id, string $secret, string $callback);
	 * @param string $id
	 * @param string $secret
	 * @param string $callback
	 * @return object
	 * @example $OAuth2 = new OAuth2('ID', 'SECRET', 'http://www.domain.com/callback.php');
	 * @author Neo Geek <neo@neo-geek.net>
	 * @copyright Copyright (c) 2012, Neo Geek
	 */
	
	public function __construct ($id, $secret, $callback) {
		
		$this->id = $id;
		$this->secret = $secret;
		$this->callback = rawurlencode($callback);
		
	}
	
	/**
	 * authenticate
	 * Redirects the user to the specified authorize URL.
	 * @method void authenticate ([string $scope]);
	 * @param string $scope (optional)
	 * @return void
	 * @example $OAuth2->authenticate();
	 * @author Neo Geek <neo@neo-geek.net>
	 * @copyright Copyright (c) 2012, Neo Geek
	 */
	
	public function authenticate ($scope = '') {
		
		$url = sprintf($this->url_authorize, $this->id, $this->callback, rawurlencode($scope));
		
		header('Location: ' . $url); exit;
		
	}
	
	/**
	 * callback
	 * Returns the callback response based on OAuth2 callback GET variables.
	 * @method void callback (function $func);
	 * @param function func
	 * @return void
	 * @example $OAuth2->callback(function($return) { echo OAuth2::parseToken($return); });
	 * @author Neo Geek <neo@neo-geek.net>
	 * @copyright Copyright (c) 2012, Neo Geek
	 */
	
	public function callback ($func) {
		
		$url = sprintf($this->url_access_token, $this->id, $this->callback, $this->secret, $_GET['code']);
		
		call_user_func($func, $this->request($url, true));
		
	}
	
	/**
	 * parseToken
	 * Return the access_token from a OAuth2 callback request.
	 * @method string|boolean parseToken (string $string);
	 * @param string $string
	 * @return string|boolean
	 * @example echo OAuth2::parseToken($return);
	 * @author Neo Geek <neo@neo-geek.net>
	 * @copyright Copyright (c) 2012, Neo Geek
	 */
	
	public static function parseToken ($string) {
		
		if (preg_match('/access_token=([^&]+)/', $string, $matches)) {
			
			return $matches[1];
			
		} else if ($string = json_decode($string, true)) {
			
			return $string['access_token'];
			
		}
		
		return false;
		
	}
	
	/**
	 * request
	 * Initiates a curl request using either GET or POST.
	 * @method string request (string $url [, boolean $post]);
	 * @param string $url
	 * @param boolean $post (optional)
	 * @return string
	 * @example echo OAuth2::request('https://api.domain.com/request');
	 * @example echo OAuth2::request('https://api.domain.com/request?response=json', true);
	 * @author Neo Geek <neo@neo-geek.net>
	 * @copyright Copyright (c) 2012, Neo Geek
	 */
	
	public function request ($url, $post = false) {
		
		$ch = curl_init($url);
		
		if (parse_url($url, PHP_URL_SCHEME) == 'https') {
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			
		}
		
		if ($post) {
			
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, parse_url($url, PHP_URL_QUERY));
			
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$output = curl_exec($ch);
		
		curl_close($ch);
		
		return $output;
		
	}
	
}

class Facebook_OAuth2 extends OAuth2 {
	
	public $url_authorize = 'https://graph.facebook.com/oauth/authorize?client_id=%s&redirect_uri=%s&scope=%s';
	public $url_access_token = 'https://graph.facebook.com/oauth/access_token?client_id=%s&redirect_uri=%s&client_secret=%s&code=%s';
	
}

class GooglePlus_OAuth2 extends OAuth2 {
	
	public $url_authorize = 'https://accounts.google.com/o/oauth2/auth?client_id=%s&redirect_uri=%s&scope=%s&response_type=code';
	public $url_access_token = 'https://accounts.google.com/o/oauth2/token?client_id=%s&redirect_uri=%s&client_secret=%s&code=%s&grant_type=authorization_code';
	
}

class GitHub_OAuth2 extends OAuth2 {
	
	public $url_authorize = 'https://github.com/login/oauth/authorize?client_id=%s&redirect_uri=%s&scope=%s';
	public $url_access_token = 'https://github.com/login/oauth/access_token?client_id=%s&redirect_uri=%s&client_secret=%s&code=%s';
	
}

?>