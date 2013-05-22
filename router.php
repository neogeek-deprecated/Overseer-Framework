<?php

# namespace NeoGeek\OverseerFramework;

/* ------------------------------------------------------------

 Overseer Framework, build 2, 2013-04-01
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
 * Router
 * Basic PHP router class.
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2013, Neo Geek
 */

if (!class_exists('Router')) {

	class Router
	{

		final static function parsePath ($path) {

			if (!isset($_SERVER['PATH_INFO'])) {

				return false;

			}

			$path = trim($path, '/');

			$path = preg_replace('/:int(eger)?/', '([0-9]+)', $path);
			$path = preg_replace('/:str(ing)?/', '([0-9a-z]+)', $path);

			$path = str_replace('/', '\/', $path);

			$server_path = trim($_SERVER['PATH_INFO'], '/');

			preg_match('/^' . $path . '/i', $server_path, $matches);

			return $matches;

		}

		final private function request ($path, $func) {

			$args = $this::parsePath($path);

			if ($args) {

				return call_user_func_array($func, array($args, $path));

			}

			return false;

		}

		final static function setContentType ($type = 'application/json') {

			if (!headers_sent()) {

				header('Content-type: ' . $type . '; charset=UTF-8');

			}

		}

		final static function setStatus ($code = 200, $status = 'OK') {

			if (!headers_sent()) {

				header('HTTP/1.1 ' . $code . ' ' . $status);

			}

		}

		final public function __call ($name, $arguments) {

			if ($_SERVER['REQUEST_METHOD'] == strtoupper($name)) {

				return call_user_func_array(array($this, 'request'), $arguments);

			}

		}

	}

}
