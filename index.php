<?php
/**
 * Name: David Kovalevich
 * Date: 01/19/2019
 * Fat Free Framework
 */

session_start();

//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Require autoload
require_once ('vendor/autoload.php');

//Create an instance of the Base class
$f3 = Base::instance();

$_SESSION['fname'] = "";
//Turn of Fat-Free error reporting
$f3->set('DEBUG', 3);

$f3->set('states', array("Alabama", "Alaska", "Arizona", "Arkansas",
    "California", "Colorado", "Connecticut", "Delaware", "Florida",
    "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas",
    "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts",
    "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana",
    "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico",
    "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma",
    "Oregon", "Pennsylvania", "Rhode Island", "South Carolina",
    "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia",
    "Washington", "West Virginia", "Wisconsin", "Wyoming"));

//Define a default route
$f3->route('GET /', function() {
    $view = new View;
    echo $view->render('views/home.html');
});
//register route
$f3->route('GET|POST /personal', function($f3) {
    $_SESSION = array();
    print_r($_POST);
    if(!empty($_POST)) {
        $isValid = true;

        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $tel = $_POST['tel'];

        echo $fname;

        $_SESSION['fname'] = $fname;
        $f3->set("fname", $fname);

        echo $_SESSION['fname'];

        $_SESSION['lname'] = $lname;
        $f3->set("lname", $lname);

        $_SESSION['age'] = $age;
        $f3->set("age", $age);

        $_SESSION['gender'] = $gender;
        $f3->set("gender", $gender);

        $_SESSION['tel'] = $tel;
        $f3->set("tel", $tel);

        $f3->reroute('/profile');
    }


    $template = new Template();
    echo $template->render('views/personal.html');
});

$f3->route('GET|POST /profile', function($f3) {
    echo $_SESSION['fname'];
    if(!empty($_POST)) {
        $isValid = true;

        $email = $_POST['email'];
        $state = $_POST['state'];
        $seeking = $_POST['seeking'];
        $biography = $_POST['biography'];

        $_SESSION['email'] = $email;
        $f3->set("email", $email);

        $_SESSION['state'] = $state;
        $f3->set("state", $state);

        $_SESSION['seeking'] = $seeking;
        $f3->set("seeking", $seeking);

        $_SESSION['biography'] = $biography;
        $f3->set("biography", $biography);

        $f3->reroute('/interests');
    }

    $template = new Template();
    echo $template->render('views/profile.html');
});

$f3->route('GET|POST /interests', function($f3) {
    if(!empty($_POST)) {
        $isValid = true;
        $indoorlist = "";
        $outdoorlist = "";

        if (!empty($_POST['indoor'])) {
            foreach ($_POST['indoor'] as $indoor) {
                $indoorlist = $indoorlist . " " . $indoor;
            }
        }

        if (!empty($_POST['outdoor'])) {
            foreach ($_POST['outdoor'] as $outdoor) {
                $outdoorlist = $outdoorlist . " " . $outdoor;
            }
        }

        $interestsList = $outdoorlist . " " . $indoorlist;

        $_SESSION['interestsList'] = $interestsList;

        $f3->reroute('/summary');
    }

    $template = new Template();
    echo $template->render('views/interests.html');
});

//summary route
$f3->route('GET|POST /summary', function($f3) {
    $f3->set('fname', $_SESSION['fname']);
    $f3->set('lname', $_SESSION['lname']);
    $f3->set('gender', $_SESSION['gender']);
    $f3->set('age', $_SESSION['age']);
    $f3->set('tel', $_SESSION['tel']);
    $f3->set('email', $_SESSION['email']);
    $f3->set('state', $_SESSION['state']);
    $f3->set('seeking', $_SESSION['seeking']);
    $f3->set('biography', $_SESSION['biography']);
    $f3->set('interests', $_SESSION['interestsList']);

    $template = new Template();
    echo $template->render('views/summary.html');
});



//Run fat free
$f3->run();
