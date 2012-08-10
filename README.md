#The Overseer Framework

A collection of simple functions and classes, the Overseer Framework is a useful addition to any PHP project. From functions for quickly retrieving data from MySQL queries to basic DOM manipulation, the Overseer Framework is packed with functions you never knew you needed.

#Functions

##check_referer

Checks the HTTP_REFERER server variable against the current or specified page.

###Method

boolean check_referer ([string $url]);
###Parameters

	string $url (optional)
###Examples

	check_referer();
	check_referer('/contact/');
##fetch_remote_file

Fetches an external file using the built-in PHP library CURL. Also allows for specifying a cached version and expiration time.

###Method

string fetch_remote_file (string $url [, filename $cache, string|integer $expire]);
###Parameters

	string $url
	filename $cache (optional)
	string|integer $expire (optional)
###Examples

	fetch_remote_file('http://www.example.com/file.xml');
	fetch_remote_file('http://www.example.com/file.xml', 'cache/file.xml', '1 hour ago');
##getbrowser

Basic alternative to the built in PHP get_browser function. Supports Opera, Google Chrome, Safari, Firefox and Internet Explorer.

###Method

array|boolean getbrowser ([string $http_user_agent]);
###Parameters

	string $http_user_agent (optional)
###Examples

	getbrowser();
	getbrowser('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8) AppleWebKit/536.25 (KHTML, like Gecko) Version/6.0 Safari/536.25')
##getcsv

Returns CSV file or string as a multidimensional array.

###Method

array getcsv (string|filename $string);
###Parameters

	string|filename $string
###Examples

	getcsv('data.csv');
##mysql_fetch_results

Returns the results of a MySQL query as an array, the number of rows affected, or the row ID inserted.

###Method

array|integer mysql_fetch_results (string|resource $query [, array $results]);
###Parameters

	string|resource $query
	array $results (optional)
###Examples

	mysql_fetch_results('INSERT INTO `user` SET `username` = "username", `password` = SHA("password")');
	mysql_fetch_results('SELECT * FROM `user` WHERE `user_id` = 1 LIMIT 1');
	mysql_fetch_results('UPDATE `user` SET `last_logged_in` = NOW() WHERE `user_id` = 1 LIMIT 1');
##mysqli_fetch_results

Returns the results of a MySQLi query as an array, the number of rows affected, or the row ID inserted.

###Method

array|integer mysqli_fetch_results (resource $resource, string|resource $query [, array $results]);
###Parameters

	resource $resource
	string|resource $query
	array $results (optional)
###Examples

	mysqli_fetch_results($mysqli, 'INSERT INTO `user` SET `username` = "username", `password` = SHA("password")');
	mysqli_fetch_results($mysqli, 'SELECT * FROM `user` WHERE `user_id` = 1 LIMIT 1');
	mysqli_fetch_results($mysqli, 'UPDATE `user` SET `last_logged_in` = NOW() WHERE `user_id` = 1 LIMIT 1');
##mysqli_transaction

Prepares and executes a MYSQLi statement. Returns the results of the MySQLi query as an array, the number of rows affected, or the row ID inserted.

###Method

array|integer mysqli_transaction (resource $resource, string $query [, string $types, string $var1, ..., string $var10]);
###Parameters

	resource $resource
	string $query
	string $types (optional)
	string $var1 (optional)
	string $var# (optional)
	string $var10 (optional)
###Examples

	mysqli_transaction($mysqli, 'INSERT INTO `user` SET `username` = ?, `password` = SHA(?)', 'ss', 'username', 'password');
	mysqli_transaction($mysqli, 'SELECT * FROM `user` WHERE `user_id` = ? LIMIT 1', 'i', 1);
	mysqli_transaction($mysqli, 'UPDATE `user` SET `last_logged_in` = NOW() WHERE `user_id` = ? LIMIT 1', 'i', 1);
##path_info

Returns virtual path names based on offset.

###Method

string|boolean path_info ([integer $offset, string $path]);
###Parameters

	integer $offset (optional)
	string $path (optional)
###Examples

	echo path_info(1);
	echo path_info(1, 'user/neogeek');
##print_array

Prints any number of arrays (or strings) to the output buffer surrounded by pre tags.

###Method

void print_array ([array $array1, ..., array $array10]);
###Parameters

	string $array1 (optional)
	string $array# (optional)
	string $array10 (optional)
###Examples

	print_array($results, $_POST);
##runtime

Returns the number of milliseconds past between function calls.

###Method

integer runtime ([int $precision]);
###Parameters

	integer $precision (optional)
###Examples

	echo 'This script took ' . runtime(2) . ' millisecond(s) to run.';
##sha

Returns a string or file encoded as sha256.

###Method

string sha (string|filename $content [, string $type]);
###Parameters

	string|filename $content
	string $type (optional)
###Examples

	echo sha('encode');
	echo sha('encode.txt', 'sha1');
Classes

##DOM

Extends the built in PHP DOMDocument class.

##$DOM->create

Creates an HTML DOM element with content and attributes utilizing only one function call.

###Method

object create (string $tag [, string|object $content, array $attribs]);
###Parameters

	string $tag
	string|object $content (optional)
	array $attribs (optional)
###Examples

	$DOM->create('p', 'Lorem ipsum dolor sit amet.', array('class'=>'demo'));
##$DOM->getElementById

Extends the default getElementById function to allow for access to imported elements.

###Method

object|boolean getElementById (string $id);
###Parameters

	string $id
###Examples

	$DOM->getElementById('test'));
##$DOM->import

Imports an external HTML source as a document fragment. (Notice: Must be valid HTML)

###Method

object import (string|filename $string);
###Parameters

	string|filename $string
###Examples

	$DOM->appendChild($DOM->import('<h1>Hello World!</h1>'));
##$DOM->nextSiblings

Returns the next sibling based on an integer.

###Method

object|boolean nextSiblings (object $object [, integer $num]);
###Parameters

	object $object
	integer $num (optional)
###Examples

	$DOM->nextSiblings($object, 5);
##$DOM->prepend

Prepends an object before the specified node.

###Method

object prepend (object $object, object $node);
###Parameters

	object $object
	object $node
###Examples

	$DOM->prepend($DOM->create('div', 'test'), $node);
##$DOM->query

Queries the DOM using XPath.

###Method

object query (string $query);
###Parameters

	string $query
###Examples

	$DOM->query('//div');
##$DOM->remove

Removes one or more HTML DOM elements.

###Method

object|boolean remove (object $object);
###Parameters

	object $object
###Examples

	$DOM->remove($DOM->getElementById('demo'));
	$DOM->remove($DOM->getElementById('demo')->getElementsByTagName('p'));
##$DOM->replace

Replaces the specified with another.

###Method

object replace (object $object, object $node);
###Parameters

	object $object
	object $node
###Examples

	$DOM->replace($DOM->create('select'), $DOM->getElementById('dropdown'));