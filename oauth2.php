<?php

# namespace NeoGeek\OverseerFramework;

/* ------------------------------------------------------------
 
 Overseer Framework, build 4, 2013-04-01
 http://overseerframework.com/
 
 Copyright (c) 2013 Neo Geek
 Dual-licensed under both MIT and BSD licenses.
 
 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:
 
 The above copyright notice and this permission notice shall be included in
 all copies or substantial portions of the Software.
 
 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 THE SOFTWARE.
 
------------------------------------------------------------ */

/**
 * OAuth2
 * A simple PHP class for establishing OAuth 2.0 connections.
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2013, Neo Geek
 */

if (!class_exists('OAuth2')) {
	
	class OAuth2
	{
		
		public $id;
		public $secret;
		public $callback;
		
		public $scope;
		public $state;
		
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
		 * @example $OAuth2 = new OAuth2('ID', 'SECRET', 'http://www.example.com/callback.php');
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2013, Neo Geek
		 */
		
		final public function __construct ($id, $secret, $callback) {
			
			$this->id = $id;
			$this->secret = $secret;
			$this->callback = rawurlencode($callback);
			
		}
		
		/**
		 * authenticate
		 * Redirects the user to the specified authorize URL.
		 * @method void OAuth2::authenticate ();
		 * @return void
		 * @example $OAuth2->authenticate();
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2013, Neo Geek
		 */
		
		final public function authenticate () {
			
			$url = sprintf($this->url_authorize, $this->id, $this->callback, rawurlencode($this->scope), $this->state);
			
			header('Location: ' . $url); exit;
			
		}
		
		/**
		 * callback
		 * Returns the callback response based on OAuth2 callback GET variables.
		 * @method void OAuth2::callback (function $func);
		 * @param function func
		 * @return void
		 * @example $OAuth2->callback(function ($return) { echo OAuth2::parseToken($return); });
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2013, Neo Geek
		 */
		
		final public function callback ($func) {
			
			$url = sprintf($this->url_access_token, $this->id, $this->secret, $this->callback, $_GET['code']);
			
			call_user_func($func, $this->request($url, true));
			
		}
		
		/**
		 * parseToken
		 * Returns the access_token from a OAuth2 callback request.
		 * @method string|boolean OAuth2::parseToken (string $string);
		 * @param string $string
		 * @return string|boolean
		 * @example echo OAuth2::parseToken($return);
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2013, Neo Geek
		 */
		
		final public static function parseToken ($string) {
			
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
		 * @method string OAuth2::request (string $url [, boolean $post]);
		 * @param string $url
		 * @param boolean $post (optional)
		 * @return string
		 * @example echo OAuth2::request('https://api.example.com/request');
		 * @example echo OAuth2::request('https://api.example.com/request?response=json', true);
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2013, Neo Geek
		 */
		
		final public function request ($url, $post = false) {
			
			$ch = curl_init($url);
			
			if (strtolower(parse_url($url, PHP_URL_SCHEME)) == 'https') {
				
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
				
			}
			
			if ($post) {
				
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, parse_url($url, PHP_URL_QUERY));
				
				$url = preg_replace('/\?.+$/', '', $url);
				
			}
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			$output = curl_exec($ch);
			
			curl_close($ch);
			
			return $output;
			
		}
		
	}
	
}

if (!class_exists('Facebook_OAuth2')) {
	
	class Facebook_OAuth2 extends OAuth2 {
		
		public $url_authorize = 'https://www.facebook.com/dialog/oauth?client_id=%s&redirect_uri=%s&scope=%s&state=%s';
		public $url_access_token = 'https://graph.facebook.com/oauth/access_token?client_id=%s&client_secret=%s&redirect_uri=%s&code=%s';
		
	}
	
}

if (!class_exists('GitHub_OAuth2')) {
	
	class GitHub_OAuth2 extends OAuth2 {
		
		public $url_authorize = 'https://github.com/login/oauth/authorize?client_id=%s&redirect_uri=%s&scope=%s&state=%s';
		public $url_access_token = 'https://github.com/login/oauth/access_token?client_id=%s&client_secret=%s&redirect_uri=%s&code=%s';
		
	}
	
}

if (!class_exists('GooglePlus_OAuth2')) {
	
	class GooglePlus_OAuth2 extends OAuth2 {
		
		public $scope = 'https://www.googleapis.com/auth/plus.me';
		
		public $url_authorize = 'https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=%s&redirect_uri=%s&scope=%s&state=%s';
		public $url_access_token = 'https://accounts.google.com/o/oauth2/token?grant_type=authorization_code&client_id=%s&client_secret=%s&redirect_uri=%s&code=%s';
		
	}
	
}
