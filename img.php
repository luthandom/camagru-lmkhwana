<?php
     session_start();

     require_once('config/database.php');
 
     $imageDir = "images/";
     $img = explode(",", $_POST['img']);
     $img = base64_decode($img[1]);
     $id = $_SESSION['id'];
     $iid = uniqid();
     if (!file_exists($imageDir)) {
         mkdir($imageDir);
     }
     file_put_contents($imageDir . $iid . '.jpeg', $img);
     $path = $iid . '.jpeg';
 
     try {
         $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
         $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $query = $db->prepare("INSERT INTO gallery (user_id, img) VALUES (?, ?)");
         $values = array($id, $path);
         $query->execute($values);
         $rowCount = $query->rowCount();
         if ($rowCount == 1) {
             header("Location: index.php");
         }
     } catch (PDOException $e) {
         return $e->getMessage();
     }
    header("Location: upload.php");
?>