<?php

/**
 * This PHP file uses the Slim Framework to construct a REST API.
 * Most of the heavy lifting happens in MongoApp.
 */
require 'vendor/autoload.php';
require_once './MongoApp.php';
$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Blade(),
    'templates.path' => './templates',
        ));

$view = $app->view();
$view->parserOptions = array(
    'debug' => true,
    'cache' => dirname(__FILE__) . '/cache-view',
);

$app->get('/api/todos', function () use ($app) {
    $app->render('master', array(
        'todolist' => MongoApp::Instance()->get(),
        'buttonSelected' => "all"
    ));
});

$app->get('/api/todos/active', function () use ($app) {
    $app->render('master', array(
        'todolist' => MongoApp::Instance()->getQuery(['completed' => false]),
        'buttonSelected' => "active"
    ));
});


$app->get('/api/todos/completed', function () use ($app) {
    $app->render('master', array(
        'todolist' => MongoApp::Instance()->getQuery(['completed' => true]),
        'buttonSelected' => "completed"
    ));
});

$app->post('/api/todos', function () use ($app) {
    $todo = json_decode($app->request()->getBody(), true);
    MongoApp::Instance()->post($todo);
});

$app->delete('/api/todos/:id', function ($id) use ($app) {
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
