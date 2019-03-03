<?php
/**
 * Created by PhpStorm.
 * User: davidkovalevich
 * Date: 3/2/19
 * Time: 5:34 PM
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once ('/home/dkovalev/config.php');

class Database {
    function connect()
    {
        try {
            $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            return $dbh;
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    function insertMember($fname, $lname, $age, $gender, $phone, $email, $state, $seeking, $bio, $premium, $image, $interests)
    {
        global $dbh;

        $insert = "INSERT INTO `members`(`fname`, `lname`, `age`, `gender`, `phone`, `email`, `state`, `seeking`, `bio`, `premium`, `image`, `interests`) VALUES ('$fname', '$lname', $age, '$gender', '$phone', '$email', '$state', '$seeking', '$bio', $premium, '$image', '$interests');";

        $statement = $dbh->prepare($insert);

        $statement->bindParam(':fname', $fname, PDO::PARAM_STR);
        $statement->bindParam(':lname', $lname, PDO::PARAM_STR);
        $statement->bindParam(':age', $age, PDO::PARAM_INT);
        $statement->bindParam(':gender', $gender, PDO::PARAM_STR);
        $statement->bindParam(':phone', $phone, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':state', $state, PDO::PARAM_STR);
        $statement->bindParam(':seeking', $seeking, PDO::PARAM_STR);
        $statement->bindParam(':bio', $bio, PDO::PARAM_STR);
        $statement->bindParam(':premium', $premium, PDO::PARAM_INT);
        $statement->bindParam(':image', $image, PDO::PARAM_STR);
        $statement->bindParam(':interests', $interests, PDO::PARAM_STR);

        $execute = $statement->execute();

        return $execute;
    }

    function getMembers()
    {
        global $dbh;

        $fetchMembers = "SELECT * FROM members ORDER BY lname";

        $statement = $dbh->prepare($fetchMembers);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function getMember($id)
    {
        global $dbh;

        $getMember = "SELECT * FROM `members` WHERE member_id = '$id';";

        $statement = $dbh->prepare($getMember);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}