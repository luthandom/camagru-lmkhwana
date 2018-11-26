<?php

require_once('database.php');

    try {
        $db = new PDO("mysql:host=$DB_HOST", $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec("DROP DATABASE IF EXISTS $DB_NAME");
        $sql = "CREATE DATABASE IF NOT EXISTS $DB_NAME";
        $db->exec($sql);
        echo "Database created successfully\n";
    }
    catch (PDOException $e) {
        echo "ERROR CREATING DB: \n".$e->getMessage()."\nAborting process\n";
        exit;
    }

    try {
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE TABLE IF NOT EXISTS `users` (
                      `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                      `username` VARCHAR (30) NOT NULL,
                      `password` varchar (255) NOT NULL,
                      `email` varchar (50),
                      `validated` VARCHAR(1) NOT NULL DEFAULT 'N',
                      `mail_pref` ENUM('0','1') NOT NULL DEFAULT '0'
                      )";
        $db->exec($sql);
        echo "users created successfully\n";
    }
    catch (PDOException $e) {
        echo "ERROR CREATING Users: \n" . $e->getMessage() . "\nAborting process\n";
        exit;
    }

    try {
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE TABLE IF NOT EXISTS `gallery` (
          `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `user_id` INT(11) NOT NULL,
          `img` VARCHAR(30) NOT NULL,
          `date_created` timestamp NOT NULL
    )";
        $db->exec($sql);
        echo "Gallery_table created successfully\n";
    }

    catch (PDOException $e) {
        echo "ERROR CREATING TABLE: ".$e->getMessage()."\nAborting process\n";
    }

    try {
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE TABLE IF NOT EXISTS `likes` (
              `user_id` INT(11) NOT NULL,
              `gallery_id` INT(11) NOT NULL
        )";
        $db->exec($sql);
        echo "Likes table created successfully\n";
    }
    catch (PDOException $e) {
        echo "ERROR CREATING TABLE: ".$e->getMessage()."\nAborting process\n";
    }

    try {
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE TABLE IF NOT EXISTS `comment` (
              `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `user_id` INT(11) NOT NULL,
              `gallery_id` INT(11) NOT NULL,
              `comment` TEXT NOT NULL, 
              `date_and_time` timestamp NOT NULL
        )";
        $db->exec($sql);
        echo "Comment table created successfully\n";
    }
    catch (PDOException $e) {
        echo "ERROR CREATING TABLE: ".$e->getMessage()."\nAborting process\n";
    }

    $conn = null;
?>