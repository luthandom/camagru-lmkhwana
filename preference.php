<?php
session_start();
$id = $_SESSION['id'];
require_once ('helpers/input_checker.php');
require_once ('config/database.php');

if (isset($_POST['submit'])) {
    @$receive = $_POST['receive'];
    @$noReceive = $_POST['noReceive'];

    if (isset($receive)) {
        try {
            $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $statement = $db->prepare("UPDATE users SET `mail_pref` = '1' WHERE id = :id");
            $statement->bindParam(":id", $id);
            $statement->execute();

            $success =  "You will now receive email notifications when someone comments on your post";

        } catch(PDOException $e) {
            echo "Error: ". $e->getMessage();
        }
    }
    elseif (isset($noReceive)) {
        try {
            $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $statement = $db->prepare("UPDATE users SET `mail_pref` = '0' WHERE id = :id");
            $statement->bindParam(":id", $id);
            $statement->execute();

            $success = "You will not receive email notifications when someone comments on your post";

        } catch(PDOException $e) {
            echo "Error: ". $e->getMessage();
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link rel="stylesheet" type="text/css" href="style/navbar.css">
</head>
<body>
<?php include 'views/header.php'; ?>
<div class="header">
    <div class="container"> <!-- container div -->
        <form action="" method="post">
            <h3 style="text-align: center">Change Email Preference</h3>

            <label for="send">Receive Email Confirmation</label>
            <input type="radio" name="receive">
            <br>
            <label for="send">Don't Receive Email Confirmation</label>
            <input type="radio" name="noReceive">

            <button type="submit" name="submit">Change</button>
            <!-- <span class="sgn">Already have an account? <a href="login.php">sign in</a></span><br> -->
            <br/>
            <?php
            if (isset($success)) {
                echo $success . "\n";
            }
            ?>
        </form>
    </div> <!-- end container div -->
    <?php include 'views/footer.php'; ?>
</body>
</html>