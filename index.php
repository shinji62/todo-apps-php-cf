<?php

/**
 * This PHP file uses the Slim Framework to construct a REST API.
 * Most of the heavy lifting happens in MongoApp.
 */
require 'vendor/autoload.php';
require_once './MongoApp.php';

//Small Helper for CF data
$vcap_application = array();
$vcap_application["ip"] = isset($_ENV["CF_INSTANCE_IP"]) ? $_ENV["CF_INSTANCE_IP"] : "unknow";
$vcap_application["index"] = isset($_ENV["CF_INSTANCE_INDEX"]) ? $_ENV["CF_INSTANCE_INDEX"] : "unknow";

$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Blade(),
    'templates.path' => './templates',
        ));

$view = $app->view();
$view->parserOptions = array(
    'debug' => true,
    'cache' => dirname(__FILE__) . '/cache-view',
);

$app->get('/api/todos', function () use ($app, $vcap_application) {
    $app->render('master', array(
        'todolist' => MongoApp::Instance()->get(),
        'buttonSelected' => "all",
        'vcapInfo' => $vcap_application
    ));
});

$app->get('/api/todos/active', function () use ($app, $vcap_application) {
    $app->render('master', array(
        'todolist' => MongoApp::Instance()->getQuery(['completed' => false]),
        'buttonSelected' => "active",
        'vcapInfo' => $vcap_application
    ));
});


$app->get('/api/todos/completed', function () use ($app, $vcap_application) {
    $app->render('master', array(
        'todolist' => MongoApp::Instance()->getQuery(['completed' => true]),
        'buttonSelected' => "completed",
        'vcapInfo' => $vcap_application
    ));
});

$app->post('/api/todos', function () use ($app, $vcap_application) {
    $todo = json_decode($app->request()->getBody(), true);
    MongoApp::Instance()->post($todo);
});

$app->delete('/api/todos/:id', function ($id) use ($app, $vcap_application) {
    if ($id == "completed") {
        MongoApp::Instance()->deleteQuery(['completed' => true]);
    } else {
        MongoApp::Instance()->delete($id);
    }
    $app->response()->status(204);
});

$app->put('/api/todos/:id', function ($id) use ($app) {
    $todo = json_decode($app->request()->getBody(), true);
    MongoApp::Instance()->put($id, $todo);
});


$app->get('/api/kill', function () {
    @exec("kill -9 -1 httpd");;
});





$app->run();
