<?php

# namespace NeoGeek\OverseerFramework;

/**
 * Router
 * Basic PHP router class.
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2012, Neo Geek
 */

if (!class_exists('Router')) {
	
	class Router
	{
		
		final private function parsePath ($path) {
			
			if (!isset($_SERVER['PATH_INFO'])) {
				
				return false;
				
			}
			
			$path = trim($path, '/');
			
			$path = preg_replace('/\{int(eger)?\}/', '([0-9]+)', $path);
			$path = preg_replace('/\{str(ing)?\}/', '([0-9a-z]+)', $path);
			
			$path = str_replace('/', '\/', $path);
			
			$server_path = trim($_SERVER['PATH_INFO'], '/');
			
			preg_match('/^' . $path . '$/i', $server_path, $matches);
			
			return $matches;
		
		}
	
		final private function request ($path, $func) {
			
			$args = $this->parsePath($path);
			
			if ($args) {
				
				return $func($args);
				
			}
			
			return false;
			
		}
	
		final public function __call ($name, $arguments) {
			
			if ($_SERVER['REQUEST_METHOD'] == strtoupper($name)) {
				
				return call_user_func_array(array($this, 'request'), $arguments);
				
			}
			
		}
		
	}
	
}

?>