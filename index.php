<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <title>CAMAGRU</title>
</head>
<body>
<?php if (isset($_SESSION['id'])) { ?>
    <?php include './views/header.php'; ?>
    <div class="row"> <!-- row div -->
        <div>
            <div class="column">
                <form>
                    <label>
                        <input type="radio">
                        <img class="thumbnail" src="img/heart.png" onclick="add(this)"/>
                    </label>
                    <label>
                        <input type="radio">
                        <img class="thumbnail" src="img/none.png" onclick="add(this)"/>
                    </label>
                    <label>
                         <input type="radio">
                        <img class="thumbnail" src="img/wow.png" onclick="add(this)"/>
                    </label>
                    <label>
                         <input type="radio">
                        <img class="thumbnail" src="img/lip.png" onclick="add(this)"/>
                    </label>
                    <label>
                         <input type="radio">
                        <img class="thumbnail" src="img/cir.png" onclick="add(this)"/>
                    </label>
                </form>
                <div>
                    <img src="" id="supImage" width="640px" height="480px" />
                    <video autoplay="true" id="video" width="640" height="480"></video>
                </div>
                <input type="button" id ="capture" class="booth-capture-button" value = "Take photo" disabled>
                <div style="align-content: center">
                    <input type="submit" style="display: none; text-decoration: none;"><a href="upload.php">Upload</a>
                </div>
                <canvas style="display: none;" id="canvas" width="640" height="480"></canvas>
            </div>
        </div>
        <div class="column">
            <h1><strong>Preview</strong></h1>
        
             <?php
            include_once ('config/database.php');

            try {
                $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $query = $db->prepare("SELECT * FROM gallery WHERE `user_id` = ? ORDER BY date_created DESC");
                $value = array($_SESSION['id']);
                $query->execute($value);

                $result = $query->fetchAll();
                foreach ($result as $v) { ?>
                        <img class="img" src="<?php echo "images/".$v['img']; ?>" />
                        <a class="btn" href="delete.php?id=<?php echo $v['id']; ?>">delete</a>
                <?php }
            } catch (PDOException $e) {
                    return $e->getMessage();
            }
                $db = null;
            ?>
            <form id="capture-form" name="capture-form" method="post" action="save.php">
                <input type="hidden" name="img" id="picture" value=""/>
            </form>
        </div>
    </div> <!-- !row div -->
    <script type="text/javascript" src="js/webcam.js"></script>
    <?php include './views/footer.php'; ?>
<?php } else {
    header("Location: login/login.php");
} ?>
</body>
</html>