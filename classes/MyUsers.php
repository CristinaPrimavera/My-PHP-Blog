<?php

/**
 * MyUsers
 *
 * Person or entity that can log in to the site
 * (as opposed to the MyArticles class, here I am adding the database columns as properties)
*/
class MyUsers
{
    public $id, $username, $password;

    /**
     * Authenticate a user by username and password
     *
     * @param object $conn Connection to the database
     * @param string $username
     * @param string $password
     *
     * @return boolean True if the credentials are correct, false otherwise
    */
    public static function authenticate($conn, $username, $password) {

        $sql = "SELECT *
                  FROM myCourseUsers
                 WHERE username = :username";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'MyUsers');
        $stmt->execute();

        if ($user = $stmt->fetch()) {
            return password_verify($password, $user->password);
        }
    }



//    public static function newUser($conn, $username, $password) {
//
//        $sql = "INSERT INTO myCourseUsers (username, password)
//                     VALUES (:username, :password)";
//
//        $stmt = $conn->prepare($sql);
//
//        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
//        $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
//
//        return $stmt->execute();
//
//    }
}