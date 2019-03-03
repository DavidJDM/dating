<?php
/**
 * Name: David Kovalevich
 * Date: 01/19/2019
 * Fat Free Framework
 */



//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Require autoload

require ('vendor/autoload.php');

session_start();


$database = new Database();

//Sets class database into session variable
$_SESSION['database'] = $database;

//Connect to database
$dbh = $database->connect();

//Create an instance of the Base class
$f3 = Base::instance();

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

//default route
$f3->route('GET /', function() {
    $view = new View;
    echo $view->render('views/home.html');
});


$f3->route('GET|POST /personal', function($f3) {
    $_SESSION = array();

    if(!empty($_POST)) {
        $isValid = true;

        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $tel = $_POST['tel'];

        if (isset($_POST['premium']))
        {
            $_SESSION['premium'] = true;
            $premiumMember = new PremiumMember($fname, $lname, $age, $gender, $tel);
            $_SESSION['member'] = $premiumMember;
            $f3->reroute('/profile');
        }
        else
        {
            $member = new Member($fname, $lname, $age, $gender, $tel);
            $_SESSION['member'] = $member;
            $f3->reroute('/profile');
        }
    }


    $template = new Template();
    echo $template->render('views/personal.html');
});

$f3->route('GET|POST /profile', function($f3) {
    if(!empty($_POST)) {
        $member = $_SESSION['member'];

        $email = $_POST['email'];
        $member->setEmail($email);

        $state = $_POST['state'];
        $member->setState($state);

        $seeking = $_POST['seeking'];
        $member->setSeeking($seeking);

        $biography = $_POST['biography'];
        $member->setBio($biography);

        $_SESSION['member'] = $member;
        if ($_SESSION['premium'] == true)
        {
            $f3->reroute('/interests');
        }
        else
        {
            $f3->reroute('/summary');
        }
    }

    $template = new Template();
    echo $template->render('views/profile.html');
});

$f3->route('GET|POST /interests', function($f3) {
    if(!empty($_POST)) {
        $member = $_SESSION['member'];
        $indoorlist = "";
        $outdoorlist = "";

        if (!empty($_POST['indoor'])) {
            foreach ($_POST['indoor'] as $indoor) {
                $indoorlist = $indoorlist . $indoor . ", ";
            }

            $member->setInDoorInterests($indoorlist);
        }

        if (!empty($_POST['outdoor'])) {
            foreach ($_POST['outdoor'] as $outdoor) {
                $outdoorlist = $outdoorlist . $outdoor . ", ";
            }

            $member->setOutDoorInterests($outdoorlist);
        }
        $_SESSION['member'] = $member;

        $interestsList = $outdoorlist . " " . $indoorlist;

        $_SESSION['interestsList'] = $interestsList;

        $f3->reroute('/summary');
    }

    $template = new Template();
    echo $template->render('views/interests.html');
});

$f3->route('GET|POST /summary', function($f3) {

    //classes
    $member = $_SESSION['member'];
    $database = $_SESSION['database'];

    $f3->set('fname', $member->getFname());
    $f3->set('lname', $member->getLname());
    $f3->set('gender', $member->getGender());
    $f3->set('age', $member->getAge());
    $f3->set('tel', $member->getPhone());
    $f3->set('email', $member->getEmail());
    $f3->set('state', $member->getState());
    $f3->set('seeking', $member->getSeeking());
    $f3->set('biography', $member->getBio());

    if($_SESSION['premium'] == false) {
        $database->insertMember($member->getFname(), $member->getLname(), $member->getAge(), $member->getGender(), $member->getPhone(), $member->getEmail(), $member->getState(), $member->getSeeking(), $member->getBio(), 0, NULL, NULL);
    }

    if($_SESSION['premium'] == true) {
        $f3->set('interests', $member->getInDoorInterests() . ", " . $member->getOutDoorInterests());
        $database->insertMember($member->getFname(), $member->getLname(), $member->getAge(), $member->getGender(), $member->getPhone(), $member->getEmail(), $member->getState(), $member->getSeeking(), $member->getBio(), 1, NULL, $member->getInDoorInterests() . " " . $member->getOutDoorInterests());
    }


    $template = new Template();
    echo $template->render('views/summary.html');
});

$f3->route('GET|POST /admin', function ($f3) {
    $database = $_SESSION['database'];
    $members = $database->getMembers();
    $f3->set('members', $members);

    $view = new Template();
    echo $view->render('views/admin.html');
});

$f3->route('GET|POST /displayMember@memberId', function($f3, $params) {
    $database = $_SESSION['database'];
    $member = $database->getMember($params['memberId']);
    $f3->set('member', $member[0]); //Gets the info of the user with the specified ID
    $view = new Template();
    echo $view->render('views/displayMember.html');
});



//Run fat free
$f3->run();
