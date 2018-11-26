<?php

    require_once ('../helpers/input_checker.php');
    require_once ('../config/database.php');
    session_start();

    if (isset($_POST['submit'])) {
        $username = check_input($_POST['username']);
        $password = check_input($_POST['password']);

        $error = "";

        if (empty($username) || empty($password)) {
            $error = "Please fill all fields";
        } else {
            $password = hash("whirlpool", $password);
            try {
                $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND validated = 'Y'");
                $value = array($username);
                $stmt->execute($value);
                $results = $stmt->fetchAll();
                $rowCount = $stmt->rowCount();
                if ($rowCount > 0) {
                    foreach ($results as $value) {
                        $id = $value['id'];
                        $user = $value['username'];
                        $pass = $value['password'];
                    }
                    if ($username === $user && $password === $pass) {
                        $_SESSION['id'] = $id;
                        header("Location: ../index.php");
                    } else {
                        $error = "Incorrect username or password";
                    }
                } else {
                    $error = "User does not exist";
                }
            } catch (PDOException $e) {
                echo "ERROR SELECTING Users: \n" . $e->getMessage() . "\nAborting process\n";
                exit;
            }
        }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">

</head>
<body>
    <?php include '../views/header.php'; ?>
    <div class="container"> <!-- container div -->
        <form action="" method="post">
                <h3 style="text-align: center">Sign In</h3>
                <label for="username"><b>Username</b></label>
                <input type="text" placeholder="Enter Username" name="username" autofocus>

                <label for="password"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="password">

                <button type="submit" name="submit">Login</button>
                <span class="sgn">Don't have an account? <a href="signup.php">sign up</a></span>
                <span class="psw">Forgot <a href="../resetpswd.php">password?</a></span>
                <?php
                if (isset($error)) {
                    echo $error . "\n";
                }
                ?>
        </form>
    </div> <!-- end container div -->
    <?php include '../views/footer.php'; ?>
</body>
</html>