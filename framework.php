<?php

/* ------------------------------------------------------------
 
 Overseer Framework, build 66, 2011-09-15 23:38:26
 
 Copyright (c) 2011 Neo Geek
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
 * check_referer
 * Checks the HTTP_REFERER server variable against the current or specified page.
 * @method boolean check_referer([string $url]);
 * @param string $url (optional)
 * @return boolean
 * @example check_referer();
 * @example check_referer('/contact/');
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2011, Neo Geek
 */

if (!function_exists('check_referer')) {

	function check_referer($url = '') {
		return isset($_SERVER['HTTP_REFERER'])?strpos($_SERVER['HTTP_REFERER'], $url?$url:$_SERVER['REQUEST_URI']) !== false:false;
	}

}

/**
 * fetch_remote_file
 * Fetches an external file using the built-in PHP library CURL.
 * @method string fetch_remote_file(string $file);
 * @param string $file
 * @return string
 * @example fetch_remote_file('http://www.example.com/file.xml');
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2011, Neo Geek
 */

if (!function_exists('fetch_remote_file')) {

	function fetch_remote_file($file) {
		
		$ch = curl_init($file);
		
		if (parse_url($file, PHP_URL_SCHEME) == 'https') {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$output = curl_exec($ch);
		
		curl_close($ch);
		
		return $output;
		
	}

}

/**
 * getbrowser
 * Basic alternative to the built in PHP get_browser function. Supports Opera, Google Chrome, Safari, Firefox and Internet Explorer.
 * @method array getbrowser([string $http_user_agent]);
 * @param string $http_user_agent (optional)
 * @return array
 * @example getbrowser();
 * @example getbrowser('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; en-us) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4')
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2011, Neo Geek
 */

if (!function_exists('getbrowser')) {
	
	function getbrowser($http_user_agent = null) {
	
		if ($http_user_agent == null) { $http_user_agent = $_SERVER['HTTP_USER_AGENT']; }
	
		if (preg_match('/Opera(?:[\/ ]([0-9.]+))?(?:.*Version[\/ ]([0-9.]+))?/i', $http_user_agent, $matches)) {
			return array('Opera', isset($matches[2])?$matches[2]:(isset($matches[1])?$matches[1]:null));
		
		} else if (preg_match('/Chrome\/([0-9.]+)?/i', $http_user_agent, $matches)) {
			return array('Google Chrome', isset($matches[1])?$matches[1]:null);
		
		} else if (preg_match('/(?:Version\/([0-9.]+).*)?Safari[\/ ][0-9.]+?/i', $http_user_agent, $matches)) {
			return array('Safari', isset($matches[1])?$matches[1]:null);
		
		} else if (preg_match('/Firefox(?:[\/ ]([0-9.]+))?/i', $http_user_agent, $matches)) {
			return array('Firefox', isset($matches[1])?$matches[1]:null);
		
		} else if (preg_match('/MSIE(?:[\/ ]([0-9.]+))?/i', $http_user_agent, $matches)) {
			return array('Internet Explorer', isset($matches[1])?$matches[1]:null);
		
		} else { return false; }
	
	}
	
}

/**
 * getcsv
 * Returns CSV file or string as an array.
 * @method array getcsv(string $string);
 * @param string $string
 * @return array
 * @example getcsv('data.csv');
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2011, Neo Geek
 */

if (!function_exists('getcsv')) {
	
	function getcsv($string) {
		if (is_file($string)) { $string = file_get_contents($string); }
		return array_map('str_getcsv', explode(PHP_EOL, $string));
	}
	
}

/**
 * mysql_fetch_results
 * Returns the results of a MySQL query as an array or the number of rows affected.
 * @method array|integer mysql_fetch_results(string|resource $query [, array $results]);
 * @param string|resource $query
 * @param array $results (optional)
 * @return array|integer
 * @example mysql_fetch_results('SELECT * FROM `user`');
 * @example mysql_fetch_results('UPDATE `user` SET `date` = NOW()');
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2011, Neo Geek
 */

if (!function_exists('mysql_fetch_results')) {
	
	function mysql_fetch_results($query, $results = array()) {
		if (is_resource($query)) { $result = $query; } else { $result = mysql_query($query); }
		if (is_resource($result)) {
			while ($row = mysql_fetch_assoc($result)) { array_push($results, $row); }
		} else { $results = mysql_affected_rows(); }
		return $results;
	}
	
}

/**
 * mysqli_fetch_results
 * Returns the results of a MySQLi query as an array or the number of rows affected.
 * @method array|integer mysqli_fetch_results(resource $resource, string|resource $query [, array $results]);
 * @param resource $resource
 * @param string|resource $query
 * @param array $results (optional)
 * @return array|integer
 * @example mysqli_fetch_results($mysqli, 'SELECT * FROM `user`');
 * @example mysqli_fetch_results($mysqli, 'UPDATE `user` SET `date` = NOW()');
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2011, Neo Geek
 */

if (!function_exists('mysqli_fetch_results')) {
	
	function mysqli_fetch_results($resource, $query, $results = array()) {
		if (is_object($query)) { $result = $query; } else { $result = $resource->query($query); }
		if (is_object($result)) {
			while ($row = $result->fetch_assoc()) { array_push($results, $row); }
			$result->close();
		} else { $results = mysqli_affected_rows($resource); }
		return $results;
	}
	
}

/**
 * mysqli_transaction
 * Prepares and executes a MYSQLi statement.
 * @method array|integer mysqli_transaction(resource $resource, string $query [, string $types, string $var1, ..., string $var10]);
 * @param resource $resource
 * @param string $query
 * @param string $types (optional)
 * @param string $var1 (optional)
 * @param string $var# (optional)
 * @param string $var10 (optional)
 * @return array|integer
 * @example mysqli_transaction($mysqli, 'INSERT INTO `user` SET `username` = ?, `password` = ?', 'ss', 'username', 'password');
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2011, Neo Geek
 */

if (!function_exists('mysqli_transaction')) {
	
	function mysqli_transaction($resource, $query, $types = '') {
		
		$attribs = array_slice(func_get_args(), 3);
		$results = $params = $tmp = array();
		
		if (!$result = $resource->prepare(preg_replace('/[\'"%]+\?[\'"%]+/', '?', $query))) { return false; }
		
		foreach ($attribs as $key => $value) { $attribs[$key] = &$attribs[$key]; }
		
		if ($types && $attribs) {
			call_user_func_array('mysqli_stmt_bind_param', array_merge(array($result, substr($types, 0, count($attribs))), $attribs));
		}
		
		$result->execute();
		
		if (!$meta = $result->result_metadata()) { return $result->affected_rows; }
		
		while ($field = $meta->fetch_field()) { $params[] = &$row[$field->name]; }
		
		call_user_func_array('mysqli_stmt_bind_result', array_merge(array($result), $params));
		
		while ($result->fetch()) {
			foreach ($row as $key => $value) { $tmp[$key] = $value; } array_push($results, $tmp);
		}
		
		$result->close();
		
		return $results;
		
	}
	
}

/**
 * path_info
 * Returns virtual path names based on offset.
 * @method string|boolean path_info([integer $offset]);
 * @param integer $offset (optional)
 * @return string|boolean
 * @example echo path_info(1);
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2011, Neo Geek
 */

if (!function_exists('path_info')) {
	
	if (!isset($_SERVER['PATH_INFO']) && isset($_SERVER['ORIG_PATH_INFO'])) { $_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO']; }
	
	function path_info($offset = 0) {
		$matches = preg_split('/\//', isset($_SERVER['PATH_INFO'], $_SERVER['SCRIPT_NAME']) && ($_SERVER['PATH_INFO'] != $_SERVER['SCRIPT_NAME'])?$_SERVER['PATH_INFO']:null, null, PREG_SPLIT_NO_EMPTY);
		return isset($matches[$offset])?$matches[$offset]:false;
	}
	
}

/**
 * print_array
 * Prints any number of arrays (or strings) to the output buffer.
 * @method boolean print_array([array $array1, ..., array $array10]);
 * @param string $array1 (optional)
 * @param string $array# (optional)
 * @param string $array10 (optional)
 * @return boolean
 * @example print_array($results, $_POST);
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2011, Neo Geek
 */

if (!function_exists('print_array')) {
	
	function print_array() {
		$arrays = func_get_args();
		foreach ($arrays as $array) { echo '<pre>' . print_r($array, true) . '</pre>'; }
		return false;
	}
	
}

/**
 * runtime
 * Returns the number of milliseconds past between function calls.
 * @method integer runtime([int $precision, int $output]);
 * @static integer $time
 * @param integer $precision (optional)
 * @param integer $output (optional)
 * @return integer
 * @example echo 'This script took ' . runtime(2) . ' millisecond(s) to run.';
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2011, Neo Geek
 */

if (!function_exists('runtime')) {
	
	function runtime($precision = 0, $output = 0) {
		static $time;
		if ($time) { $output = round((microtime(true) - (float)$time) * 10000, $precision); }
		$time = microtime(true);
		return $output;
	}
	
}

/**
 * sha
 * Returns a string or file encoded as sha256.
 * @method string sha(string|filename $content [, string $type]);
 * @param string|filename $content
 * @param string $type (optional)
 * @return string
 * @example echo sha('encode');
 * @example echo sha('encode.txt', 'sha1');
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2011, Neo Geek
 */

if (!function_exists('sha')) {
	
	function sha($content = '', $type = 'sha256') {
		if (is_file($content)) { $content = file_get_contents($content); }
		return hash($type, $content);
	}
	
}

/**
 * DOM
 * Extends the built in PHP DOMDocument class.
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2011, Neo Geek
 */

if (!class_exists('DOM')) {
	
	class DOM extends DOMDocument
	{
		
		/**
		 * create
		 * Creates an HTML DOM element with content and attributes utilizing only one function call.
		 * @method object create(string $tag, [string|object $content, array $attribs]);
		 * @param string $tag
		 * @param string|object $content (optional)
		 * @param array $attribs (optional)
		 * @return object
		 * @example $DOM->create('p', 'Lorem ipsum dolor sit amet.', array('class'=>'demo'));
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2011, Neo Geek
		 */
		
		function create($tag = '', $content = null, $attribs = array()) {
			$element = $this->createElement($tag);
			if (is_object($content)) { $element->appendChild($content); } else { $element->appendChild($this->createTextNode((string)$content)); }
			foreach ($attribs as $key => $value) { $element->setAttribute((is_string($key)?$key:(string)$value), $value!==null?(string)$value:null); }
			return $element;
		}
		
		/**
		 * getElementById
		 * Extends the default getElementById function to allow for access to imported elements.
		 * @method object getElementById(string $name);
		 * @param string $name
		 * @return object
		 * @example $DOM->getElementById('test'));
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2011, Neo Geek
		 */
		
		function getElementById($name = '') {
			if ($element = parent::getElementById($name)) { return $element; } else {
				$elements = $this->getElementsByTagName('*');
				foreach ($elements as $element) {
					if ($element->getAttribute('id') == $name) { return $element; }
				}
			}
			return false;
		}
		
		/**
		 * import
		 * Imports an external HTML source as a document fragment. (Notice: Must be valid HTML)
		 * @method object import(string $string);
		 * @param string $string
		 * @return object
		 * @example $DOM->appendChild($DOM->import('<h1>Hello World!</h1>'));
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2011, Neo Geek
		 */
		
		function import($string = '') {
			$element = $this->createDocumentFragment();
			$element->appendXML($string);
			return $element;
		}
		
		/**
		 * nextSiblings
		 * Returns the next sibling based on an integer.
		 * @method object nextSiblings(object $object, [integer $num]);
		 * @param object $object
		 * @param integer $num (optional)
		 * @return object
		 * @example $DOM->nextSiblings($object, 5);
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2011, Neo Geek
		 */
		
		function nextSiblings($object, $num = 1) {
			while ($num) { $object = $object->nextSibling; $num--; }
			return $object;
		}
		
		/**
		 * prependChild
		 * Prepends an object before the specific node.
		 * @method object prependChild(object $object, object $node);
		 * @param object $object
		 * @param object $node
		 * @return object
		 * @example $DOM->prependChild($DOM->create('div', 'test'), $node);
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2011, Neo Geek
		 */
		
		function prependChild($object, $node) {
			$node->parentNode->insertBefore($object, $node);
		}
		
		/**
		 * remove
		 * Removes an HTML DOM element.
		 * @method boolean remove(object $object);
		 * @param object $object
		 * @return boolean
		 * @example $DOM->remove($DOM->getElementById('demo'));
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2011, Neo Geek
		 */
		
		function remove($object) {
			if (is_object($object)) { return $object->parentNode->removeChild($object); }
			return false;
		}
		
	}
	
}

?>