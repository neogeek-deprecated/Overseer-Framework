<?php

# namespace NeoGeek\OverseerFramework;

/**
 * Router
 * Basic PHP router class.
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2012, Neo Geek
 */

class Router
{
	
	private final function parsePath ($path) {
		
		if (!isset($_SERVER['PATH_INFO'])) {
			
			return false;
			
		}
		
		$path = trim($path, '/');
		
		$path = preg_replace('/\{int(eger)?\}/', '([0-9]+)', $path);
		$path = preg_replace('/\{str(ing)?\}/', '([0-9a-z]+)', $path);
		
		$path = str_replace('/', '\/', $path);
		
		preg_match('/^' . $path . '$/i', trim($_SERVER['PATH_INFO'], '/'), $matches);
		
		return $matches;
		
	}
	
	private final function request ($path, $func) {
		
		if ($args = $this->parsePath($path)) {
			
			return $func($args);
			
		}
		
		return false;
		
	}
	
	public final function __call ($name, $arguments) {
		
		if ($_SERVER['REQUEST_METHOD'] == strtoupper($name)) {
			
			return call_user_func_array(array($this, 'request'), $arguments);
			
		}
		
	}
	
}

?>