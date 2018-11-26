<?php
    session_start();

    if (isset($_SESSION['id'])) {
    require_once ('config/database.php');

    $id = $_GET['id'];
    $uid = $_SESSION['id'];

    try {
            $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query = $db->prepare("SELECT * FROM gallery WHERE id=? AND user_id=$uid LIMIT 1");
            $val = array($id);
            $query->execute($val);
            $res = $query->fetchAll();
            foreach ($res as $v) {
                $img = $v['img'];
            }
            $delFile = "images/".$img;
            // Delete image from the images folder
            unlink($delFile);

            // Delete image from database
            $sql = "DELETE FROM gallery WHERE id=$id AND user_id=$uid LIMIT 1";
            $db->exec($sql);
            
            header('location: gallery.php');
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    
        $conn = null;
    } else {
        header('location: login/login.php');
    }
?>