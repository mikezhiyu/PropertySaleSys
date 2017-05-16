<?php

session_cache_limiter(false);
session_start();

require_once 'vendor/autoload.php';

//DB::$host = '127.0.0.1';
DB::$user = 'propertySaleSys';
DB::$password = '3oMYbW6fBYaTBsum';
DB::$dbName = 'propertySaleSys';
DB::$port = 3333;
DB::$encoding = 'utf8';

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

$app->get('/', function() use ($app) {
    $app->render('start.html.twig');
});

$app->run();
