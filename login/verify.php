<?php
    include_once '../config/database.php';
    
    function redirect() {
        header("Location: ../index.php");
        exit();
    }

    if (!isset($_GET['username'])) {
        redirect();
    } else {
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $username = $_GET['username'];

        
        $stmt = $db->prepare("UPDATE users SET validated='Y' WHERE username='$username'");
        $stmt->execute();

        redirect();
    }
?>