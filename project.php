<?php

session_cache_limiter(false);
session_start();
//rWVaKK@0pETJ
//User: cp4776_pro-em 
//Database: cp4776_propertymanagement

require_once 'vendor/autoload.php';
//local db pass : 2VA1kc6eTnoiPUTf
DB::$user = 'root';
DB::$password = "";
DB::$dbName = 'propertymanagement';
DB::$port = 3307;
DB::$encoding = 'utf8';



/* DB::$user = 'cp4776_pro-em ';
  DB::$dbName = 'cp4776_propertymanagement';
  DB::$password = "rWVaKK@0pETJ"; */

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

$app->get('/', function() use ($app) {
    $app->render('index.html.twig');
});

// STATE 1: First show


$app->get('/register', function() use ($app) {
    $app->render('register.html.twig');
});


// Receiving a submission
$app->post('/register', function() use ($app) {
    // extract variables
    $email = $app->request()->post('email');
    $pass1 = $app->request()->post('password1');
    $pass2 = $app->request()->post('password2');
    $firstname = $app->request()->post('firstname');
    $lastname = $app->request()->post('lastname');
    // list of values to retain after a failed submission
    $valueList = array(
        'email' => $email,
        'firstname' =>$firstname,
        'lastname' => $lastname
    );
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
     if (strlen($firstname) < 2 || strlen($firstname) > 50 || empty($firstname)) {
            array_push($errorList, "First Name too short or empty, must be 2 characters or longer");
        }
     if (strlen($lastname) < 2 || strlen($lastname) > 50 || empty($lastname)) {
            array_push($errorList, "Last Name too short or empty, must be 2 characters or longer");
        }
    
    if ($pass1 != $pass2) {
        array_push($errorList, "Passwords do not match");
    } else {
        if (strlen($pass1) < 6) {
            array_push($errorList, "Password too short, must be 6 characters or longer");
        }
        if (preg_match('/[A-Z]/', $pass1) != 1 || preg_match('/[a-z]/', $pass1) != 1 || preg_match('/[0-9]/', $pass1) != 1) {
            array_push($errorList, "Password must contain at least one lowercase, "
                    . "one uppercase letter, and a digit");
        }
    }
    //
    if ($errorList) {
        $app->render('register.html.twig', array(
            'errorList' => $errorList,
            'v' => $valueList
        ));
    } else {
        DB::insert('users', array(
            'email' => $email,
            'password' => $pass1,
            'name' => $lastname
        ));
        $app->render('register_success.html.twig');
    }
});

// AJAX: Is user with this email already registered?
$app->get('/ajax/emailused/:email', function($email) {
    $user = DB::queryFirstRow("SELECT * FROM users WHERE email=%s", $email);
    //echo json_encode($user, JSON_PRETTY_PRINT);
    echo json_encode($user != null);
});


//log in

$app->get('/login', function() use ($app) {
    $app->render('login.html.twig');
});

$app->post('/login', function() use ($app) {
    $email = $app->request()->post('email');
    $pass = $app->request()->post('password');
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
    //print_r($user);
    // decide what to render
    if ($error) {
        $app->render('login.html.twig', array("error" => true));
    } else {
        unset($user['password']);
        $_SESSION['user'] = $user;
        $app->render('login_success.html.twig');
    }
});

$app->get('/logout', function() use ($app) {
    unset($_SESSION['user']);
    $app->render('logout.html.twig');
});

//
$app->get('/session', function() {
    print_r($_SESSION);
   
});



$app->get('/property', function() use ($app) {
    $app->render('propertydetails.html.twig');
});

$app->get('/list', function() use ($app) {
    $app->render('list_property.html.twig');
});





///add and update

/*$app->get('/addproperty/:op(/:id)', function($op, $id = 0) use ($app) {
    /* FOR PROJECTS WITH MANY ACCESS LEVELS
      if (($_SESSION['user']) || ($_SESSION['level'] != 'admin')) {
      $app->render('forbidden.html.twig');
      return;
      } */
   /* if ($op == 'edit') {
        $house = DB::queryFirstRow("SELECT * FROM houses WHERE id=%i", $id);
        if (!$house) {
            echo 'Product not found';
            return;
        }
        $app->render("add_property.html.twig", array(
            'v' => $house, 'operation' => 'Update'
        ));
    } else {
        $app->render("add_property.html.twig", array('operation' => 'Add'
        ));
    }
})->conditions(array(
    'op' => '(add|edit)',
    'id' => '[0-9]+'));*/

/*$app->post('/addproperty/:op(/:id)', function($op, $id = 0) use ($app) {
    $postalcode = $app->request()->post('postCode');
    $address = $app->request()->post('address');
    $image = isset($_FILES['image']) ? $_FILES['image'] : array();
    $phoneNumber = $app->request()->post('phoneNumber');
    $price = $app->request()->post('Price');
    $numberofbedroom = $app->request()->post('numberOfBedroom');
    $year = $app->request()->post('yearOfBuild');
    $status = $app->request()->post('status');
    $valueList = array('postCode' => $postalcode, 'address' => $address,
        'phoneNumber' => $phoneNumber);

    $image = $_FILES['image'];
    // print_r($image);
    //    
    $errorList = array();
    if (strlen($address) < 2 || strlen($address) > 300) {
        array_push($errorList, "Address must be 2-300 characters long");
    }
    if (strlen($status) < 2 || strlen($description) > 50) {
        array_push($errorList, "status must be 2-50 characters long");
    }
    if (empty($price) || $price < 0 || $price > 99999999) {
        array_push($errorList, "Price must be between 0 and 99999999");
    }
    if ($image['error'] != 0) {
        array_push($errorList, "Image is required to create a product");
    } else {
        $imageInfo = getimagesize($image["tmp_name"]);
        if (!$imageInfo) {
            array_push($errorList, "File does not look like an valid image");
        } else {
            // FIXME: opened a security hole here! .. must be forbidden
            if (strstr($image["name"], "..")) {
                array_push($errorList, "File name invalid");
            }
            // FIXME: only allow select extensions .jpg .gif .png, never .php
            $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, array('jpg', 'jpeg', 'gif', 'png'))) {
                array_push($errorList, "File name invalid");
            }
            // FIXME: do not allow file to override an previous upload
            if (file_exists('uploads/' . $image['name'])) {
                array_push($errorList, "File name already exists. Will not override.");
            }
        }
    }
    //
    if ($errorList) {
        $app->render("add_property.html.twig", array(
            'v' => $valueList,
            "errorList" => $errorList,
            'operation' => ($op == 'edit' ? 'Edit' : 'Update')
        ));
    } else {

        $imagePath = "uploads/" . $image['name'];
        move_uploaded_file($image["tmp_name"], $imagePath);
        if ($op == 'edit') {
            // unlink('') OLD file - requires select            
            $oldImagePath = DB::queryFirstField(
                            'SELECT imagePath FROM houses WHERE id=%i', $id);
            if (($oldImagePath) && file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            DB::update('houses', array(
                'ownerId' => $ownerId,
                'postCode' => $postalcode,
                'address' => $address,
                'phoneNumber' => $phoneNumber,
                'numberOfBedroom' => $numberofbedroom,
                'Price' => $price,
                'yearOfBuild' => $year,
                'status' => $status
                    ), "id=%i", $id);
/// do I have to do this? fo both tables?

            Db::update('imagepaths', array(
                'imageData' => $imageBinaryData,
                'imageMimeType' => $mimeType), $id);
        } else {
            DB::insert('products', array(
                'ownerId' => $ownerId,
                'postCode' => $postalcode,
                'address' => $address,
                'phoneNumber' => $phoneNumber,
                'numberOfBedroom' => $numberofbedroom,
                'Price' => $price,
                'yearOfBuild' => $year,
                'status' => $status,
                'imageData' => $imageBinaryData,
                'imageMimeType' => $mimeType
            ));

            Db::insert('imagepaths', array(
                'imageData' => $imageBinaryData,
                'imageMimeType' => $mimeType), $id);
        }
        $app->render("property_add_success.html.twig", array(
            "imagePath" => $imagePath
        ));
    }
})->conditions(array(
    'op' => '(add|edit)',
    'id' => '[0-9]+'));*/






$app->run();
