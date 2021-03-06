<?php

   require_once ('../helpers/input_checker.php');
   require_once ('../config/database.php');

    if (isset($_POST['submit'])) {
        $username = check_input($_POST['username']);
        $password = check_input($_POST['password']);
        $re_password = check_input($_POST['re-password']);
        $email = check_input($_POST['email']);

        $error = "";

        if (empty($username) || empty($password) || empty($re_password) || empty($email)) {
            $error = "Please fill all fields";
        }
        else if (strlen($username) > 20 || strlen($username) < 4) {
            $error = "Username should be between 4 and 20 characters";
        }
        else if (strlen($password) < 6)
        {
            $error = "Password must be atleast 6 characters";
        }
        else if (!preg_match('/[0-9]/', $password))
        {
            $error = "Password must have atleast one numeric ";
        }
        elseif(!preg_match('/[A-Z]/', $password)){
            $error = "Password must have at least one uppercase letter";
        }
        elseif (!preg_match('/[\'^£$%&*()}{@#~?!><>,|=_+¬-]/', $password))
        {
            $error = "Password must have at least one special character";
        }
        else if ($password != $re_password) {
            $error = "Password do not match";
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email";
        }
        else {
            $password = hash("whirlpool", $password);
            try {
                $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
                $value = array($username);
                $stmt->execute($value);
                $rowCount = $stmt->rowCount();
                if ($rowCount > 0) {
                    $error = "Username already taken please try another username";
                } else {
                    $sql = "INSERT INTO users(username,password,email) VALUES ('$username', '$password', '$email')";
                     $db->exec($sql);


                    $to = $email;
                    $subject = "Verify";
                    $txt = "registration complete! please confirm your email below.
                    http://localhost:8080/camagru/login/verify.php?username=$username";
                    $headers = "From: lmkhwana@camagru.com" . "\r\n" .
                    "CC: somebodyelse@example.com";
                    
                    mail($to,$subject,$txt,$headers);
                      
                      
                    
                              echo "registration complete! please confirm your email";
                              
                          }

                        }
                          catch(PDOException $e) {
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
    <link rel="stylesheet" type="text/css" href="../style/style.css">

</head>
<body>
    <?php include '../views/header.php'; ?>
    <div class="header">
    <div class="container"> <!-- container div -->
        <form action="" method="post">
            <h3 style="text-align: center">Sign Up</h3>

            <label for="email"><b>Email</b></label>
            <input type="email" placeholder="Enter email" name="email" autofocus>

            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username">

            <label for="password"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password">

            <label for="re-password"><b>Re-Password</b></label>
            <input type="password" placeholder="Re-Enter Password" name="re-password">

            <button type="submit" name="submit">Sign Up</button>
            <span class="sgn">Already have an account? <a href="login.php">sign in</a></span><br>
            <br/>
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