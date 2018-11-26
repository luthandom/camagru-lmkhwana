<?php
    session_start();
    require_once ('config/database.php');

    if (isset($_SESSION['id'])) {

    if (isset($_GET['id'])) {
        $iid = $_GET['id'];
        $uid = $_SESSION['id'];

        try {
            $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $smtp = $db->prepare("SELECT * FROM likes WHERE `user_id` = ? AND gallery_id = ?");
            $smtp->execute(array($uid, $iid));
            $rowCount = $smtp->rowCount();
            if ($rowCount == 1) {
                header("Location: gallery.php");
            } else {
                $sql = $db->prepare("INSERT INTO likes (`user_id`, gallery_id) VALUES (?, ?)");
                $values = array($uid, $iid);
                $sql->execute($values);
                header("Location: gallery.php");
            }
        }
        catch(PDOException $e)
        {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    
} else {
    header('location: login/login.php');
}
?>