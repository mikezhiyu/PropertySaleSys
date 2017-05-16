<?php

session_cache_limiter(false);
session_start();
//rWVaKK@0pETJ
//User: cp4776_pro-em 
//Database: cp4776_propertymanagement

require_once 'vendor/autoload.php';


DB::$user = 'cp4776_pro-em ';
DB::$dbName = 'cp4776_propertymanagement';
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

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = array();
}
$twig = $app->view()->getEnvironment();
$twig->addGlobal('user', $_SESSION['user']);

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

//???????get data from modal and save it into data base

$connect = mysqli_connect("localhost",  "cp4776_propertymanagement","rWVaKK@0pETJ" ,"property");
if(isset($_post["username"])) {
    
    $query = "SELECT * FROM users WHERE name='".$_POST["name"]."' AND password = '".$_POST["password"]."'";
    $result = mysqli_query($connect, $query);
    if(mysqli_num_rows($result) > 0) {
        
        $_SESSION['user'] = $_post['name'];
        echo 'Yes';
        
    }   else 
    {
        echo 'No';
        
    }
    
}


//register
/*$app->post('/index', function() use ($app) {
    // extract variables
    $email = $app->request()->post('email');
    $name = $app->request()->post('name');
    $password = $app->request()->post('password');
    // list of values to retain after a failed submission
   // $valueList = array('email' => $email);
    // check for errors and collect error messages
    $errorList = array();
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
        array_push($errorList, "Email is invalid");
    } else {
        $user = DB::queryFirstRow("SELECT * FROM users WHERE email=%s", $email);
        if ($user) {
            array_push($errorList, "Email already in use");
        }
    }
   
        if (strlen($pass1) < 6) {
            array_push($errorList, "Password too short, must be 6 characters or longer");
        } 
        if (preg_match('/[A-Z]/', $pass1) != 1
         || preg_match('/[a-z]/', $pass1) != 1
         || preg_match('/[0-9]/', $pass1) != 1) {
            array_push($errorList, "Password must contain at least one lowercase, "
                    . "one uppercase letter, and a digit");
        }
    
    //
    if ($errorList) {
        $app->render('index.html.twig', array(
            'errorList' => $errorList,
            'v' => $valueList
        ));
    } else {
        DB::insert('users', array(
            'email' => $email,
            'password' => $password
        ));
       // $app->render('register_success.html.twig');
    }
});

// AJAX: Is user with this email already registered?
$app->get('/ajax/emailused/:email', function($email) {
    $user = DB::queryFirstRow("SELECT * FROM users WHERE email=%s", $email);
    //echo json_encode($user, JSON_PRETTY_PRINT);
    echo json_encode($user != null);    
});

*/
// login form
/* $app->get('/login', function() use ($app) {
  $app->render('login.html.twig');
  }); */
/*$app->post('/index', function() use ($app) {
//    print_r($_POST);    
    $email = $app->request()->post('email');
    $pass = $app->request()->post('pass');
    // verification    
    $error = false;
    $user = DB::queryFirstRow("SELECT * FROM users WHERE email=%s", $email);
    if (!$user) {
        $error = true;
    } else {
        if ($user['password'] != $pass) {
            $error = true;
        }
    }
    // decide what to render
    if ($error) {
        $app->render('index.html.twig', array("error" => true));
    } else {
        unset($user['password']);
        $_SESSION['user'] = $user;
       // $app->render('login_success.html.twig');
    }
});
*/


$app->run();
