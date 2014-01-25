<?php

require('framework.php');
require('router.php');

$app = new Router;

// curl http://example.com/examples/router/status

$app->get('/status', function () {

	Router::setContentType('application/json');
	Router::setStatus(200, 'OK');

	die(json_encode(array('status'=>'OK')));

});

// curl http://example.com/examples/router/user/1

$app->get('/user/:int', function ($params) {

	Router::setContentType('application/json');
	Router::setStatus(200, 'OK');

	die(json_encode(array('status'=>'OK', 'data'=>array('user_id'=>$params[1], 'username'=>'neogeek'))));

});

// curl -X PUT -d "email=neo@neo-geek.net" http://example.com/examples/router/user/1

$app->put('/user/:int', function ($params) {

	Router::setContentType('application/json');
	Router::setStatus(200, 'OK');

	parse_str(file_get_contents('php://input'), $_PUT);

	die(json_encode(array('status'=>'OK', 'data'=>array('user_id'=>$params[1], 'username'=>'neogeek', 'email'=>$_PUT['email']))));

});

// curl -X DELETE http://example.com/examples/router/user/1

$app->delete('/user/:int', function ($params) {

	Router::setContentType('application/json');
	Router::setStatus(200, 'OK');

	die(json_encode(array('status'=>'OK', 'data'=>array())));

});
