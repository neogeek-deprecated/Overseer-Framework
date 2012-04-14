<?php

/**
 * OAuth2
 * A simple PHP class for establishing OAuth 2.0 connections.
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2012, Neo Geek
 */

class OAuth2 {
	
	public $id = '';
	public $secret = '';
	public $callback = '';
	
	/**
	 * __construct
	 * Sets up a new OAuth2 object.
	 * @method object OAuth2(string $id, string $secret, string $callback);
	 * @param string $id
	 * @param string $secret
	 * @param string $callback
	 * @return object
	 * @example $OAuth2 = new OAuth2('ID', 'SECRET', 'http://www.domain.com/callback.php');
	 * @author Neo Geek <neo@neo-geek.net>
	 * @copyright Copyright (c) 2012, Neo Geek
	 */
	
	public function __construct($id, $secret, $callback) {
		
		$this->id = $id;
		$this->secret = $secret;
		$this->callback = rawurlencode($callback);
		
	}
	
	/**
	 * request
	 * Initiates a curl request using either GET or POST.
	 * @method string request(string $url [, boolean $post]);
	 * @param string $url
	 * @param boolean $post (optional)
	 * @return string
	 * @example echo OAuth2::request('https://api.domain.com/request');
	 * @example echo OAuth2::request('https://api.domain.com/request?response=json', true);
	 * @author Neo Geek <neo@neo-geek.net>
	 * @copyright Copyright (c) 2012, Neo Geek
	 */
	
	public function request($url, $post = false) {
		
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
	
	public $url_authorize = 'https://graph.facebook.com/oauth/authorize?client_id=%s&redirect_uri=%s';
	public $url_access_token = 'https://graph.facebook.com/oauth/access_token?client_id=%s&redirect_uri=%s&client_secret=%s&code=%s';
	
	public function Authenticate() {
		
		$url = sprintf($this->url_authorize, $this->id, $this->callback);
		
		header('Location: ' . $url); exit;
		
	}
	
	public function Callback($func) {
		
		$url = sprintf($this->url_access_token, $this->id, $this->callback, $this->secret, $_GET['code']);
		
		call_user_func($func, $this->request($url));
		
	}
	
}

class Google_Plus_OAuth2 extends OAuth2 {
	
	public $scope = 'https://www.googleapis.com/auth/plus.me https://www.googleapis.com/auth/userinfo.profile';
	public $offline_access = false;
	
	public $url_authorize = 'https://accounts.google.com/o/oauth2/auth?client_id=%s&redirect_uri=%s&scope=%s&response_type=code';
	public $url_access_token = 'https://accounts.google.com/o/oauth2/token?client_id=%s&redirect_uri=%s&client_secret=%s&code=%s&grant_type=authorization_code';
	
	public function Authenticate() {
		
		$url = sprintf($this->url_authorize, $this->id, $this->callback, rawurlencode($this->scope));
		
		if ($this->offline_access) { $url .= '&access_type=offline'; }
		
		header('Location: ' . $url); exit;
		
	}
	
	public function Callback($func) {
		
		$url = sprintf($this->url_access_token, $this->id, $this->callback, $this->secret, $_GET['code']);
		
		call_user_func($func, $this->request($url, true));
		
	}
	
}

class GitHub_OAuth2 extends OAuth2 {
	
	public $scope = 'user';
	
	public $url_authorize = 'https://github.com/login/oauth/authorize?client_id=%s&redirect_uri=%s&scope=%s';
	public $url_access_token = 'https://github.com/login/oauth/access_token?client_id=%s&redirect_uri=%s&client_secret=%s&code=%s';
	
	public function Authenticate() {
		
		$url = sprintf($this->url_authorize, $this->id, $this->callback, $this->scope);
		
		header('Location: ' . $url); exit;
		
	}
	
	public function Callback($func) {
		
		$url = sprintf($this->url_access_token, $this->id, $this->callback, $this->secret, $_GET['code']);
		
		call_user_func($func, $this->request($url, true));
		
	}
	
}

?>