<?php
/**
 * Created by PhpStorm.
 * User: davidkovalevich
 * Date: 3/2/19
 * Time: 5:34 PM
 */

class database
{
    public function connect()
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

    public function insertMember($fname, $lname, $age, $gender, $phone, $email, $state, $seeking, $bio, $premium, $image, $interests)
    {
        $sql = "INSERT INTO `members`(`fname`, `lname`, `age`, `gender`, `phone`, `email`, `state`, `seeking`, `bio`, `premium`, `image`, `interests`) VALUES ('$fname', '$lname', $age, '$gender', '$phone', '$email', '$state', '$seeking', '$bio', $premium, '$image', '$interests');";

    }

    public function getMembers()
    {

    }

    public function getMember($id)
    {

    }
}