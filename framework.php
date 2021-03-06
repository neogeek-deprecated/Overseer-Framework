<?php

# namespace NeoGeek\OverseerFramework;

/* ------------------------------------------------------------

 Overseer Framework, build 92, 2014-01-30
 http://overseerframework.com/

 Copyright (c) 2014 Neo Geek
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
 * @method boolean check_referer ([string $url]);
 * @param string $url (optional)
 * @return boolean
 * @example check_referer();
 * @example check_referer('/contact/');
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2014, Neo Geek
 */

if (!function_exists('check_referer')) {

	function check_referer ($url = '') {

		if (!$url) {

			$url = $_SERVER['REQUEST_URI'];

		}

		if (isset($_SERVER['HTTP_REFERER'])) {

			return strpos($_SERVER['HTTP_REFERER'], $url) !== false;

		}

		return false;

	}

}

/**
 * fetch_remote_file
 * Fetches an external file using the built-in PHP library CURL. Also allows for specifying a cached version and expiration time.
 * @method string fetch_remote_file (string $url [, filename $cache, string|integer $expire]);
 * @param string $url
 * @param filename $cache (optional)
 * @param string|integer $expire (optional)
 * @return string
 * @example fetch_remote_file('http://www.example.com/file.xml');
 * @example fetch_remote_file('http://www.example.com/file.xml', 'cache/file.xml', '1 hour ago');
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2014, Neo Geek
 */

if (!function_exists('fetch_remote_file')) {

	function fetch_remote_file ($url, $cache = '', $expire = -1) {

		if (!is_numeric($expire)) {

			$expire = strtotime($expire);

		}

		if (!file_exists($cache) || !$expire || filemtime($cache) < $expire) {

			$ch = curl_init($url);

			if (strtolower(parse_url($url, PHP_URL_SCHEME)) == 'https') {

				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			}

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$content = curl_exec($ch);

			curl_close($ch);

			if ($cache) {

				file_put_contents($cache, $content);

			}

		} else {

			$content = file_get_contents($cache);

		}

		return $content;

	}

}

/**
 * getbrowser
 * Basic alternative to the built in PHP get_browser function. Supports Opera, Google Chrome, Safari, Firefox and Internet Explorer.
 * @method array|boolean getbrowser ([string $http_user_agent]);
 * @param string $http_user_agent (optional)
 * @return array|boolean
 * @example getbrowser();
 * @example getbrowser('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/536.30.1 (KHTML, like Gecko) Version/6.0.5 Safari/536.30.1')
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2014, Neo Geek
 */

if (!function_exists('getbrowser')) {

	function getbrowser ($http_user_agent = null) {

		if ($http_user_agent == null) {

			$http_user_agent = $_SERVER['HTTP_USER_AGENT'];

		}

		$browsers = array(
			'Opera' => '/Opera(?:[\/ ]([0-9.]+))?(?:.*Version[\/ ]([0-9.]+))?/i',
			'Google Chrome' => '/Chrome\/([0-9.]+)?/i',
			'Safari' => '/(?:Version\/([0-9.]+).*)?Safari[\/ ][0-9.]+?/i',
			'Firefox' => '/Firefox(?:[\/\( ]?([0-9.]+))?/i',
			'Internet Explorer' => '/MSIE(?:[\/ ]([0-9.]+))?/i'
		);

		foreach ($browsers as $browser => $regex) {

			if (preg_match($regex, $http_user_agent, $matches)) {

				if (isset($matches[2])) {

					$version = $matches[2];

				} else if (isset($matches[1])) {

					$version = $matches[1];

				} else {

					$version = null;

				}

				return array($browser, $version);

			}

		}

		return false;

	}

}

/**
 * getcsv
 * Returns CSV file or string as a multidimensional array.
 * @method array getcsv (string|filename $string);
 * @param string|filename $string
 * @return array
 * @example getcsv('data.csv');
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2014, Neo Geek
 */

if (!function_exists('getcsv')) {

	function getcsv ($string) {

		if (is_file($string)) {

			$string = file_get_contents($string);

		}

		$lines = preg_split('/\n|\r/', $string, null, PREG_SPLIT_NO_EMPTY);

		return array_map('str_getcsv', $lines);

	}

}

/**
 * markdown
 * Basic implementation of the Markdown interpreter.
 * @method string markdown (string|filename $string);
 * @param string|filename $string
 * @return string
 * @example markdown('#Headline');
 * @example markdown('file.md');
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2014, Neo Geek
 */

if (!function_exists('markdown')) {

	function markdown ($string) {

		if (is_file($string)) {

			$string = file_get_contents($string);

		}

		$rules = array(

			// Headers
			array(
				'regex' => '/(^|\n)(#{1,6})\s*([^#\n]+)#*/',
				'callback' => function ($matches) {

					$count = strlen($matches[2]);

					$string = sprintf('<h%d>%s</h%d>', $count, $matches[3], $count);

					return $matches[1] . $string . $matches[1];

				}
			),

			// Horizontal Rules
			array(
				'regex' => '/(^|\n)(?:\-| \-|\*){3,}(?=\n\n|$)/',
				'replace' => '\1<hr>\2'
			),

			// Ordered List
			array(
				'regex' => '/(^|\n)[0-9]+\.\s+(.+?)(?=\n\n|$)/s',
				'callback' => function ($matches) {

					$items = preg_split('/\n[0-9]+\.\s+/', $matches[2], null, PREG_SPLIT_NO_EMPTY);

					foreach ($items as &$item) {

						$item = preg_replace('/\n\s*/', '<br>', $item);

					}

					return $matches[1] . '<ol><li>' . implode('</li><li>', $items) . '</li></ol>' . $matches[1];

				}
			),

			// Unordered List
			array(
				'regex' => '/(^|\n)[\-\*]\s+(.+?)(?=\n\n|$)/s',
				'callback' => function ($matches) {

					$items = preg_split('/\n[\-\*]\s+/', $matches[2], null, PREG_SPLIT_NO_EMPTY);

					foreach ($items as &$item) {

						$item = preg_replace('/\n\s*/', '<br>', $item);

					}

					return $matches[1] . '<ul><li>' . implode('</li><li>', $items) . '</li></ul>' . $matches[1];

				}
			),

			// Blockquotes
			array(
				'regex' => '/(^|\n)>{1,}\s*(.+?)(?=\n\n|$)/s',
				'callback' => function ($matches) {

					$string = preg_replace('/\n>{1,}\s*/', ' ', $matches[2]);

					return $matches[1] . '<blockquote>' . $string . '</blockquote>' . $matches[1];

				}
			),

			// Code Blocks (Github Style)
			array(
				'regex' => '/(^|[\n]+)```([^\n]+)?(.+?)```([\n]+|$)/s',
				'callback' => function ($matches) {

					$string = htmlentities($matches[3]);

					$string = preg_replace('/\n/', '&#010;', $string);

					if ($matches[2]) {

						return $matches[1] . '<pre class="' . $matches[2] . '">' . $string . '</pre>' . $matches[4];

					} else {

						return $matches[1] . '<pre>' . $string . '</pre>' . $matches[4];

					}

				}
			),

			// Code Blocks
			array(
				'regex' => '/(^|\n)\t(.+?)(?=\n\n|$)/s',
				'callback' => function ($matches) {

					$string = htmlentities($matches[2]);

					$string = preg_replace('/(\n)\t/', '\1', $string);
					$string = preg_replace('/\n/', '&#010;', $string);

					return $matches[1] . '<pre>' . $string . '</pre>' . $matches[1];

				}
			),

			// Paragraphs
			array(
				'regex' => '/(^|\n)([^<\n].+[^>\n])(?=\n\n|$)/m',
				'replace' => '\1<p>\2</p>\1'
			),

			// Images
			array(
				'regex' => '/\!\[([^\]]+)\]\(([^"\)]+)(?:\s+"(.+)")?\)/',
				'callback' => function ($matches) {

					if (isset($matches[3])) {

						return sprintf('<img src="%s" alt="%s" title="%s">', $matches[2], $matches[1], $matches[3]);

					} else {

						return sprintf('<img src="%s" alt="%s">', $matches[2], $matches[1]);

					}

				}
			),

			// Anchor
			array(
				'regex' => '/\[([^\]]+)\]\(id:([^"\)]+)\)/i',
				'replace' => '<span id="\2">\1</span>'
			),

			// Anchors
			array(
				'regex' => '/\[([^\]]+)\]\(([^"\)]+)(?:\s+"(.+)")?\)/',
				'callback' => function ($matches) {

					if (isset($matches[3])) {

						return sprintf('<a href="%s" title="%s">%s</a>', $matches[2], $matches[3], $matches[1]);

					} else {

						return sprintf('<a href="%s">%s</a>', $matches[2], $matches[1]);

					}

				}
			),

			// Anchors/Link (Shorthand)
			array(
				'regex' => '/<(http(?:s)?\:\/\/[^\>]+)>/i',
				'replace' => '<a href="\1">\1</a>'
			),

			// Anchors/Email (Shorthand)
			array(
				'regex' => '/<([^>]+@.+\.[^<]+)>/',
				'replace' => '<a href="mailto:\1">\1</a>'
			),

			// Code (Literal Backticks)
			array(
				'regex' => '/`{2}([^\s]?.*?[^\s]?)`{2}/',
				'callback' => function ($matches) {

					$string = htmlentities($matches[1]);

					$string = str_replace('`', '&#96;', $string);

					return '<code>' . $string . '</code>';

				}
			),

			// Code
			array(
				'regex' => '/`([^\s]?.*?[^\s]?)`/',
				'callback' => function ($matches) {

					return '<code>' . htmlentities($matches[1]) . '</code>';

				}
			),

			// Bold
			array(
				'regex' => '/(?:\*|_){2}([^\s]?.*?[^\s]?)(?:\*|_){2}/',
				'replace' => '<strong>\1</strong>'
			),

			// Italics
			array(
				'regex' => '/(?:\*|_)([^\s]?.*?[^\s]?)(?:\*|_)/',
				'replace' => '<em>\1</em>'
			),

			// Strikethrough
			array(
				'regex' => '/~{2}([^\s]?.*?[^\s]?)~{2}/',
				'replace' => '<del>\1</del>'
			)

		);

		foreach ($rules as $rule) {

			if (isset($rule['regex'], $rule['replace'])) {

				$string = preg_replace($rule['regex'], $rule['replace'], $string);

			} else if (isset($rule['regex'], $rule['callback'])) {

				$string = preg_replace_callback($rule['regex'], $rule['callback'], $string);

			}

		}

		return $string;

	}

}

/**
 * mysql_fetch_results
 * Returns the results of a MySQL query as an array, the number of rows affected, or the row ID inserted.
 * @method array|integer mysql_fetch_results (string|resource $query [, array $results]);
 * @param string|resource $query
 * @param array $results (optional)
 * @return array|integer
 * @example mysql_fetch_results('INSERT INTO `user` SET `username` = "username", `password` = SHA("password")');
 * @example mysql_fetch_results('SELECT * FROM `user` WHERE `user_id` = 1 LIMIT 1');
 * @example mysql_fetch_results('UPDATE `user` SET `last_logged_in` = NOW() WHERE `user_id` = 1 LIMIT 1');
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2014, Neo Geek
 */

if (!function_exists('mysql_fetch_results')) {

	function mysql_fetch_results ($query, $results = array()) {

		if (is_resource($query)) {

			$result = $query;

		} else {

			$result = mysql_query($query);

		}

		if (is_resource($result)) {

			while ($row = mysql_fetch_assoc($result)) {

				array_push($results, $row);

			}

		} else {

			$results = mysql_insert_id();

			if (!$results) {

				$results = mysql_affected_rows();

			}

		}

		return $results;

	}

}

/**
 * mysqli_fetch_results
 * Returns the results of a MySQLi query as an array, the number of rows affected, or the row ID inserted.
 * @method array|integer mysqli_fetch_results (resource $resource, string|resource $query [, array $results]);
 * @param resource $resource
 * @param string|resource $query
 * @param array $results (optional)
 * @return array|integer
 * @example mysqli_fetch_results($mysqli, 'INSERT INTO `user` SET `username` = "username", `password` = SHA("password")');
 * @example mysqli_fetch_results($mysqli, 'SELECT * FROM `user` WHERE `user_id` = 1 LIMIT 1');
 * @example mysqli_fetch_results($mysqli, 'UPDATE `user` SET `last_logged_in` = NOW() WHERE `user_id` = 1 LIMIT 1');
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2014, Neo Geek
 */

if (!function_exists('mysqli_fetch_results')) {

	function mysqli_fetch_results ($resource, $query, $results = array()) {

		if (is_object($query)) {

			$result = $query;

		} else {

			$result = $resource->query($query);

		}

		if (is_object($result)) {

			while ($row = $result->fetch_assoc()) {

				array_push($results, $row);

			}

			$result->free();

		} else {

			$results = mysqli_insert_id($resource);

			if (!$results) {

				$results = mysqli_affected_rows($resource);

			}

		}

		while ($resource->more_results()) {

			$resource->next_result();

		}

		return $results;

	}

}

/**
 * mysqli_transaction
 * Prepares and executes a MYSQLi statement. Returns the results of the MySQLi query as an array, the number of rows affected, or the row ID inserted.
 * @method array|integer mysqli_transaction (resource $resource, string $query [, string $types, string $var1, ..., string $var10]);
 * @param resource $resource
 * @param string $query
 * @param string $types (optional)
 * @param string $var1 (optional)
 * @param string $var# (optional)
 * @param string $var10 (optional)
 * @return array|integer
 * @example mysqli_transaction($mysqli, 'INSERT INTO `user` SET `username` = ?, `password` = SHA(?)', 'ss', 'username', 'password');
 * @example mysqli_transaction($mysqli, 'SELECT * FROM `user` WHERE `user_id` = ? LIMIT 1', 'i', 1);
 * @example mysqli_transaction($mysqli, 'UPDATE `user` SET `last_logged_in` = NOW() WHERE `user_id` = ? LIMIT 1', 'i', 1);
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2014, Neo Geek
 */

if (!function_exists('mysqli_transaction')) {

	function mysqli_transaction ($resource, $query) {

		$result = $resource->prepare($query);

		if (!$result) {

			return false;

		}

		if (func_num_args() > 3) {

			$values = array_slice(func_get_args(), 2);

			foreach ($values as $key => $value) {

				$values[$key] = &$values[$key];

			}

			call_user_func_array(array($result, 'bind_param'), $values);

		}

		$result->execute();

		$meta = $result->result_metadata();

		if ($meta) {

			$fields = $meta->fetch_fields();

			foreach ($fields as $key => $value) {

				$fields[$value->name] = &$fields[$value->name];

				unset($fields[$key]);

			}

			call_user_func_array(array($result, 'bind_result'), $fields);

			$results = array();

			while ($result->fetch()) {

				$row = array();

				foreach ($fields as $key => $value) {

					$row[$key] = $value;

				}

				array_push($results, $row);

			}

		} else {

			$results = $result->insert_id;

			if (!$results) {

				$results = $result->affected_rows;

			}

		}

		$result->free_result();

		$result->close();

		while ($resource->more_results()) {

			$resource->next_result();

		}

		return $results;

	}

}

/**
 * path_info
 * Returns virtual path names based on offset.
 * @method string|boolean path_info ([integer $offset, string $path]);
 * @param integer $offset (optional)
 * @param string $path (optional)
 * @return string|boolean
 * @example echo path_info(1);
 * @example echo path_info(1, 'user/neogeek');
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2014, Neo Geek
 */

if (!function_exists('path_info')) {

	function path_info ($offset = 0, $path = null) {

		if (!$path) {

			$path = $_SERVER['PATH_INFO'];

		}

		$matches = preg_split('/\//', $path, null, PREG_SPLIT_NO_EMPTY);

		if (isset($matches[$offset])) {

			return $matches[$offset];

		}

		return false;

	}

}

/**
 * print_array
 * Prints any number of arrays (or strings) to the output buffer surrounded by pre tags.
 * @method void print_array ([array $array1, ..., array $array10]);
 * @param string $array1 (optional)
 * @param string $array# (optional)
 * @param string $array10 (optional)
 * @return void
 * @example print_array($results, $_POST);
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2014, Neo Geek
 */

if (!function_exists('print_array')) {

	function print_array () {

		$arrays = func_get_args();

		foreach ($arrays as $array) {

			echo '<pre>' . print_r($array, true) . '</pre>';

		}

	}

}

/**
 * runtime
 * Returns the number of milliseconds past between function calls.
 * @method integer runtime ([int $precision]);
 * @static integer $time
 * @param integer $precision (optional)
 * @return integer
 * @example echo 'This script took ' . runtime(2) . ' millisecond(s) to run.';
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2014, Neo Geek
 */

if (!function_exists('runtime')) {

	function runtime ($precision = 0) {

		static $time;

		if ($time) {

			$output = round((microtime(true) - (float)$time) * 10000, $precision);

		} else {

			$output = 0;

		}

		$time = microtime(true);

		return $output;

	}

}

/**
 * sha
 * Returns a string or file encoded as sha256.
 * @method string sha (string|filename $content [, string $type]);
 * @param string|filename $content
 * @param string $type (optional)
 * @return string
 * @example echo sha('encode');
 * @example echo sha('encode.txt', 'sha1');
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2014, Neo Geek
 */

if (!function_exists('sha')) {

	function sha ($content, $type = 'sha256') {

		if (is_file($content)) {

			$content = file_get_contents($content);

		}

		return hash($type, $content);

	}

}

/**
 * DOM
 * Extends the built in PHP DOMDocument class.
 * @author Neo Geek <neo@neo-geek.net>
 * @copyright Copyright (c) 2014, Neo Geek
 */

if (!class_exists('DOM')) {

	class DOM extends \DOMDocument
	{

		/**
		 * create
		 * Creates an HTML DOM element with content and attributes utilizing only one function call.
		 * @method object DOM::create (string $tag [, string|object $content, array $attribs]);
		 * @param string $tag
		 * @param string|object $content (optional)
		 * @param array $attribs (optional)
		 * @return object
		 * @example $DOM->create('p', 'Lorem ipsum dolor sit amet.', array('class'=>'demo'));
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2014, Neo Geek
		 */

		final public function create ($tag, $content = null, $attribs = array()) {

			$element = $this->createElement($tag);

			if (is_object($content)) {

				$element->appendChild($content);

			} else {

				$element->appendChild($this->createTextNode((string)$content));

			}

			foreach ($attribs as $key => $value) {

				if (!is_string($key)) {

					$key = (string)$value;

				}

				$element->setAttribute($key, $value);

			}

			return $element;

		}

		/**
		 * getElementById
		 * Extends the default getElementById function to allow for access to imported elements.
		 * @method object|boolean DOM::getElementById (string $id);
		 * @param string $id
		 * @return object|boolean
		 * @example $DOM->getElementById('test'));
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2014, Neo Geek
		 */

		final public function getElementById ($id) {

			$element = parent::getElementById($id);

			if (!$element) {

				$elements = $this->getElementsByTagName('*');

				foreach ($elements as $element) {

					if ($element->getAttribute('id') == $id) {

						return $element;

					}

				}

			} else {

				return $element;

			}

			return false;

		}

		/**
		 * import
		 * Imports an external HTML source as a document fragment. (Notice: Must be valid HTML)
		 * @method object DOM::import (string|filename $string);
		 * @param string|filename $string
		 * @return object
		 * @example $DOM->appendChild($DOM->import('<h1>Hello World!</h1>'));
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2014, Neo Geek
		 */

		final public function import ($string) {

			$element = $this->createDocumentFragment();

			if (is_file($string)) {

				$string = file_get_contents($string);

			}

			$element->appendXML($string);

			return $element;

		}

		/**
		 * innerHTML
		 * Returns the inner HTML of the specified node.
		 * @method string DOM::innerHTML (object $object);
		 * @param object $object
		 * @return string
		 * @example echo $DOM->innerHTML($node);
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2014, Neo Geek
		 */

		final public function innerHTML ($object) {

			$output = '';

			foreach ($object->childNodes as $node) {

				$output .= $this->saveHTML($node);

			}

			return $output;

		}

		/**
		 * nextSiblings
		 * Returns the next sibling based on an integer.
		 * @method object|boolean DOM::nextSiblings (object $object [, integer $num]);
		 * @param object $object
		 * @param integer $num (optional)
		 * @return object|boolean
		 * @example $DOM->nextSiblings($object, 5);
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2014, Neo Geek
		 */

		final public function nextSiblings ($object, $num = 1) {

			while ($num) {

				$object = $object->nextSibling;

				if ($object) {

					if ($object->nodeType == XML_ELEMENT_NODE) {

						$num--;

					}

				} else {

					return false;

				}

			}

			return $object;

		}

		/**
		 * prepend
		 * Prepends an object before the specified node.
		 * @method object DOM::prepend (object $object, object $node);
		 * @param object $object
		 * @param object $node
		 * @return object
		 * @example $DOM->prepend($DOM->create('div', 'test'), $node);
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2014, Neo Geek
		 */

		final public function prepend ($object, $node) {

			return $node->parentNode->insertBefore($object, $node);

		}

		/**
		 * query
		 * Queries the DOM using XPath.
		 * @method object DOM::query (string $query);
		 * @param string $query
		 * @return object
		 * @example $DOM->query('//div');
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2014, Neo Geek
		 */

		final public function query ($query) {

			$xpath = new DOMXPath($this);

			return $xpath->query($query);

		}

		/**
		 * remove
		 * Removes one or more HTML DOM elements.
		 * @method object|boolean DOM::remove (object $object);
		 * @param object $object
		 * @return object|boolean
		 * @example $DOM->remove($DOM->getElementById('demo'));
		 * @example $DOM->remove($DOM->getElementById('demo')->getElementsByTagName('p'));
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2014, Neo Geek
		 */

		final public function remove ($object) {

			if (isset($object->length)) {

				while ($object->length) {

					$this->remove($object->item($object->length -1));

				}

				return true;

			} else if (is_object($object)) {

				return $object->parentNode->removeChild($object);

			}

			return false;

		}

		/**
		 * replace
		 * Replaces the specified node with another object.
		 * @method object DOM::replace (object $object, object $node);
		 * @param object $object
		 * @param object $node
		 * @return object
		 * @example $DOM->replace($DOM->create('select'), $DOM->getElementById('dropdown'));
		 * @author Neo Geek <neo@neo-geek.net>
		 * @copyright Copyright (c) 2014, Neo Geek
		 */

		final public function replace ($object, $node) {

			return $node->parentNode->replaceChild($object, $node);

		}

	}

}
