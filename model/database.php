<?php
/**
 * Created by PhpStorm.
 * User: davidkovalevich
 * Date: 3/2/19
 * Time: 5:34 PM
 */

//Show Errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Info for DB conection
require_once ('/home/dkovalev/config.php');

class Database {
    //Connects to Database
    function connect()
    {
        try { //Try connecting to database
            $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            return $dbh;
        }
        catch(PDOException $e) { //If connection unsuccessful, throw an exception
            echo $e->getMessage();
            return false;
        }
    }

    //Inserts a Member into the database
    function insertMember($fname, $lname, $age, $gender, $phone, $email, $state, $seeking, $bio, $premium, $image, $interests)
    {
        global $dbh;
        $insert = "INSERT INTO `members`(`fname`, `lname`, `age`, `gender`, `phone`, `email`, `state`, `seeking`, `bio`, `premium`, `image`, `interests`) VALUES ('$fname', '$lname', $age, '$gender', '$phone', '$email', '$state', '$seeking', '$bio', $premium, '$image', '$interests');"; //SQL Statement
        $statement = $dbh->prepare($insert); //Prepare Statement
        $execute = $statement->execute(); //Execute
        return $execute;
    }

    //Returns all of the members registered on the site
    function getMembers()
    {
        global $dbh;
        $fetchMembers = "SELECT * FROM members ORDER BY lname"; //SQL Statement
        $statement = $dbh->prepare($fetchMembers); //Prepare Statement
        $statement->execute(); //Execute
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //Gets the specified members information based on ID provided
    function getMember($id)
    {
        global $dbh;
        $getMember = "SELECT * FROM `members` WHERE member_id = '$id';"; //SQL Statement
        $statement = $dbh->prepare($getMember); //Prepare Statement
        $statement->execute(); //Execute
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}