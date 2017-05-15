<?php

session_cache_limiter(false);
session_start();
//rWVaKK@0pETJ
//User: cp4776_pro-em 
//Database: cp4776_propertymanagement

require_once 'vendor/autoload.php';


DB::$user='cp4776_pro-em ';
DB::$dbName='cp4776_propertymanagement';
DB::$password = "rWVaKK@0pETJ";

// Slim creation and setup
$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Twig()
        ));

$view = $app->view();
$view->parserOptions = array(
    'debug' => true,
    'cache' => dirname(__FILE__) . '/cache'
);
$view->setTemplatesDirectory(dirname(__FILE__) . '/templates');

if (!isset($_SESSION['todouser'])) {
    $_SESSION['todouser'] = array();
}
$twig = $app->view()->getEnvironment();
$twig->addGlobal('todouser', $_SESSION['todouser']);

// STATE 1: First show
$app->get('/master', function() use ($app) {
    $app->render('master.html.twig');
});

$app->get('/index', function() use ($app) {
    $app->render('index.html.twig');
});

$app->get('/property', function() use ($app) {
    $app->render('propertydetails.html.twig');
});

// login form
$app->get('/login', function() use ($app) {
    $app->render('login.html.twig');
});


$app->run();
