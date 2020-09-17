<?php

/**
 * Database
 *
 * A connection to the database
*/
class CrisDatabase {


    /**
     * Get the databasse connection
     *
     * @return PDO object Connection to the database server
     */
    public function getConn() {

        $host = "nrdocker_mysql";
        $database = "cristina_test";
        $user = "root";
        $pw = "root";

        $dsn = 'mysql:host=' . $host . ';dbname=' . $database;

        return new PDO($dsn, $user, $pw);
    }
}
