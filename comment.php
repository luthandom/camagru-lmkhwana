<?php
    session_start();
    require_once ('config/database.php');
    require_once ('helpers/input_checker.php');

    if (isset($_SESSION['id'])) {

    if (isset($_GET['id']) && isset($_POST['submit'])) {
        $iid = check_input($_GET['id']);
        $uid = $_SESSION['id'];
        $msg = check_input($_POST['msg']);
        
        if(!empty($msg)) {
            try {
                $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $sql = $db->prepare("INSERT INTO comment (user_id, gallery_id, comment) VALUES (?, ?, ?)");
                $values = array($uid, $iid, $msg);
                $sql->execute($values);

                $sql = $db->prepare("
                            SELECT `gallery`.*,
                                    `users`.* 
                            FROM `gallery`
                            JOIN `users`
                            ON  `gallery`.`user_id` = `users`.`id`
                            WHERE `gallery`.`id` = ?
                        ");
                $sql->execute(array($iid));
                $result = $sql->fetchAll();
                foreach ($result as $value) {
                    $username = $value['username'];
                    $email = $value['email'];
                    $mail_pref = $value['mail_pref'];
                }
                
                $to = $email;
                $subject = "Comment";
                $txt = "A user commented on your photo! click on the below link to see the comment.;
                http://localhost:8080/camagru/gallery.php";
                $headers = "From: lmkhwana@camagru.com" . "\r\n" .
                "CC: somebodyelse@example.com";

                if ($mail_pref === 1) {
                    mail($to,$subject,$txt,$headers);
                }
                        
                echo "registration complete! please confirm your email";
                header("Location: gallery.php");
            } catch(PDOException $e) {
                echo "<br>" . $e->getMessage();
            }
        } else
            header('location: gallery.php');
        $conn = null;
    }

} else {
    header('location: login/login.php');
}
    
?>