<?php

session_cache_limiter(false);
session_start();
//rWVaKK@0pETJ
//User: cp4776_pro-em 
//Database: cp4776_propertymanagement

require_once 'vendor/autoload.php';


DB::$user = 'property';
//DB::$user = 'cp4776_pro-em ';
DB::$dbName = 'propertysalesys';
// DB::$dbName = 'propertymanagement';
DB::$password = "LCtPJJm6hAxgSrw0";

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

$app->get('/list', function() use ($app) {
    $app->render('list_property.html.twig');
});

//???????get data from modal and save it into data base

/* $connect = mysqli_connect("localhost",  "cp4776_propertymanagement","rWVaKK@0pETJ" ,"property");
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
 */


//add properties to sale
$app->get('/add', function() use ($app) {
  // if (!$_SESSION['user']) {
   //   $app->render('forbidden.html.twig');
    //    return;
   // }
    $app->render('add_property.html.twig');
});

$app->post('/add', function() use ($app) {
    //if (!$_SESSION['user']) {
    //    $app->render('forbidden.html.twig');
      //  return;
  //  }
    print_r($_POST);
    // print_r($_FILES);
    // extract variables
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
    // verify inputs
    $errorList = array();
    if (strlen($address) < 2 || strlen($address) > 200) {
        array_push($errorList, "address must be between 2 and 200 characters");
    }

    if (empty($postalcode)) {
        array_push($errorList, "please enter the postal code");
    }
    if ($image) {
        $imageInfo = getimagesize($image["tmp_name"]);
        if (!$imageInfo) {
            array_push($errorList, "File does not look like an valid image");
        } else {
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            if ($width > 300 || $height > 300) {
                array_push($errorList, "Image must at most 300 by 300 pixels");
            }
        }
    }
    // receive data and insert
    if (!$errorList) {
        $imageBinaryData = file_get_contents($image['tmp_name']);
      //  $ownerId = $_SESSION['user']['id'];
        $mimeType = mime_content_type($image['tmp_name']);
        DB::insert('houses', array(
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

        $app->render('add_success.html.twig');
    } else {
        // TODO: keep values entered on failed submission
        $app->render('add_property.html.twig', array(
            'v' => $valueList
        ));
    }
});





$app->run();
