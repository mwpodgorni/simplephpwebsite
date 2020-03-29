<?php

    $config = include "config.php";

    function OpenConnection()
    {
        global $config;
        $db = $config["database"];

        $dbhost = $db["dbhost"];
        $dbname = $db["dbname"];
        $dbuser = $db["dbuser"];
        $dbpass = $db["dbpass"];

        try
        {
            $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        }
        catch (PDOException $e)
        {
            die("Connection failed: " . $e->getMessage());
        }

        return $conn;
    }

    function CloseConnection($conn)
    {
        $conn = null;
    }
?>