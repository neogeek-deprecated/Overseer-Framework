#The Overseer Framework

>A collection of simple functions and classes, the Overseer Framework is a useful addition to any PHP project. From functions for quickly retrieving data from MySQL queries to basic DOM manipulation, the Overseer Framework is packed with functions you never knew you needed.

##Functions

###check_referer()

Checks the HTTP_REFERER server variable against the current or specified page.

####Method

```php
boolean check_referer ([string $url]);
```

####Parameters

```php
string $url (optional)
```

####Examples

```php
check_referer();
check_referer('/contact/');
```

###fetch_remote_file()

Fetches an external file using the built-in PHP library CURL. Also allows for specifying a cached version and expiration time.

####Method

```php
string fetch_remote_file (string $url [, filename $cache, string|integer $expire]);
```

####Parameters

```php
string $url
filename $cache (optional)
string|integer $expire (optional)
```

####Examples

```php
fetch_remote_file('http://www.example.com/file.xml');
fetch_remote_file('http://www.example.com/file.xml', 'cache/file.xml', '1 hour ago');
```

###getbrowser()

Basic alternative to the built in PHP get_browser function. Supports Opera, Google Chrome, Safari, Firefox and Internet Explorer.

####Method

```php
array|boolean getbrowser ([string $http_user_agent]);
```

####Parameters

```php
string $http_user_agent (optional)
```

####Examples

```php
getbrowser();
getbrowser('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/536.30.1 (KHTML, like Gecko) Version/6.0.5 Safari/536.30.1')
```

###getcsv()

Returns CSV file or string as a multidimensional array.

####Method

```php
array getcsv (string|filename $string);
```

####Parameters

```php
string|filename $string
```

####Examples

```php
getcsv('data.csv');
```

###markdown()

Basic implementation of the Markdown interpreter.

####Method

```php
string markdown (string|filename $string);
```

####Parameters

```php
string|filename $string
```

####Examples

```php
markdown('#Headline');
markdown('file.md');
```

###mysql_fetch_results()

Returns the results of a MySQL query as an array, the number of rows affected, or the row ID inserted.

####Method

```php
array|integer mysql_fetch_results (string|resource $query [, array $results]);
```

####Parameters

```php
string|resource $query
array $results (optional)
```

####Examples

```php
mysql_fetch_results('INSERT INTO `user` SET `username` = "username", `password` = SHA("password")');
mysql_fetch_results('SELECT * FROM `user` WHERE `user_id` = 1 LIMIT 1');
mysql_fetch_results('UPDATE `user` SET `last_logged_in` = NOW() WHERE `user_id` = 1 LIMIT 1');
```

###mysqli_fetch_results()

Returns the results of a MySQLi query as an array, the number of rows affected, or the row ID inserted.

####Method

```php
array|integer mysqli_fetch_results (resource $resource, string|resource $query [, array $results]);
```

####Parameters

```php
resource $resource
string|resource $query
array $results (optional)
```

####Examples

```php
mysqli_fetch_results($mysqli, 'INSERT INTO `user` SET `username` = "username", `password` = SHA("password")');
mysqli_fetch_results($mysqli, 'SELECT * FROM `user` WHERE `user_id` = 1 LIMIT 1');
mysqli_fetch_results($mysqli, 'UPDATE `user` SET `last_logged_in` = NOW() WHERE `user_id` = 1 LIMIT 1');
```

###mysqli_transaction()

Prepares and executes a MYSQLi statement. Returns the results of the MySQLi query as an array, the number of rows affected, or the row ID inserted.

####Method

```php
array|integer mysqli_transaction (resource $resource, string $query [, string $types, string $var1, ..., string $var10]);
```

####Parameters

```php
resource $resource
string $query
string $types (optional)
string $var1 (optional)
string $var# (optional)
string $var10 (optional)
```

####Examples

```php
mysqli_transaction($mysqli, 'INSERT INTO `user` SET `username` = ?, `password` = SHA(?)', 'ss', 'username', 'password');
mysqli_transaction($mysqli, 'SELECT * FROM `user` WHERE `user_id` = ? LIMIT 1', 'i', 1);
mysqli_transaction($mysqli, 'UPDATE `user` SET `last_logged_in` = NOW() WHERE `user_id` = ? LIMIT 1', 'i', 1);
```

###path_info()

Returns virtual path names based on offset.

####Method

```php
string|boolean path_info ([integer $offset, string $path]);
```

####Parameters

```php
integer $offset (optional)
string $path (optional)
```

####Examples

```php
echo path_info(1);
echo path_info(1, 'user/neogeek');
```

###print_array()

Prints any number of arrays (or strings) to the output buffer surrounded by pre tags.

####Method

```php
void print_array ([array $array1, ..., array $array10]);
```

####Parameters

```php
string $array1 (optional)
string $array# (optional)
string $array10 (optional)
```

####Examples

```php
print_array($results, $_POST);
```

###runtime()

Returns the number of milliseconds past between function calls.

####Method

```php
integer runtime ([int $precision]);
```

####Parameters

```php
integer $precision (optional)
```

####Examples

```php
echo 'This script took ' . runtime(2) . ' millisecond(s) to run.';
```

###sha()

Returns a string or file encoded as sha256.

####Method

```php
string sha (string|filename $content [, string $type]);
```

####Parameters

```php
string|filename $content
string $type (optional)
```

####Examples

```php
echo sha('encode');
echo sha('encode.txt', 'sha1');
```

<h2>##Classes</h2><h3>###DOM</h3><p>Extends the built in PHP DOMDocument class.</p>###$DOM->create()

Creates an HTML DOM element with content and attributes utilizing only one function call.

####Method

```php
object DOM::create (string $tag [, string|object $content, array $attribs]);
```

####Parameters

```php
string $tag
string|object $content (optional)
array $attribs (optional)
```

####Examples

```php
$DOM->create('p', 'Lorem ipsum dolor sit amet.', array('class'=>'demo'));
```

###$DOM->getElementById()

Extends the default getElementById function to allow for access to imported elements.

####Method

```php
object|boolean DOM::getElementById (string $id);
```

####Parameters

```php
string $id
```

####Examples

```php
$DOM->getElementById('test'));
```

###$DOM->import()

Imports an external HTML source as a document fragment. (Notice: Must be valid HTML)

####Method

```php
object DOM::import (string|filename $string);
```

####Parameters

```php
string|filename $string
```

####Examples

```php
$DOM->appendChild($DOM->import('<h1>Hello World!</h1>'));
```

###$DOM->innerHTML()

Returns the inner HTML of the specified node.

####Method

```php
string DOM::innerHTML (object $object);
```

####Parameters

```php
object $object
```

####Examples

```php
echo $DOM->innerHTML($node);
```

###$DOM->nextSiblings()

Returns the next sibling based on an integer.

####Method

```php
object|boolean DOM::nextSiblings (object $object [, integer $num]);
```

####Parameters

```php
object $object
integer $num (optional)
```

####Examples

```php
$DOM->nextSiblings($object, 5);
```

###$DOM->prepend()

Prepends an object before the specified node.

####Method

```php
object DOM::prepend (object $object, object $node);
```

####Parameters

```php
object $object
object $node
```

####Examples

```php
$DOM->prepend($DOM->create('div', 'test'), $node);
```

###$DOM->query()

Queries the DOM using XPath.

####Method

```php
object DOM::query (string $query);
```

####Parameters

```php
string $query
```

####Examples

```php
$DOM->query('//div');
```

###$DOM->remove()

Removes one or more HTML DOM elements.

####Method

```php
object|boolean DOM::remove (object $object);
```

####Parameters

```php
object $object
```

####Examples

```php
$DOM->remove($DOM->getElementById('demo'));
$DOM->remove($DOM->getElementById('demo')->getElementsByTagName('p'));
```

###$DOM->replace()

Replaces the specified node with another object.

####Method

```php
object DOM::replace (object $object, object $node);
```

####Parameters

```php
object $object
object $node
```

####Examples

```php
$DOM->replace($DOM->create('select'), $DOM->getElementById('dropdown'));
```
