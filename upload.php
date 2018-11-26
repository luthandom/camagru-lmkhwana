<?php
    session_start();

if (isset($_POST['submit'])) {
    $dir = "uploads/";
    $file_name = $_FILES['uploaded_img']['name'];
    $temp = $_FILES['uploaded_img']['tmp_name'];
    $file_n = $dir . $file_name;
    if (!file_exists($dir)) {
        mkdir($dir, 0755);
    }
    if (move_uploaded_file($temp, $file_n)) {
        echo "The file " . $_FILES["fileToUpload"]["name"] . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
<!DOCTYPE html>
<HTML>
<head>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
</head>
<body>
<?php if (isset($_SESSION['id'])) { ?>
    <?php include('views/header.php') ?>
    <div class="cam">
        <div class="row">
            <div class="column">
                <div style="align-content: center">
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
                    <div id="frme">
                        <img src="" id="superimg" alt="" width="640px" height="480px" />
                        <img src="<?php echo $file_n;?>" id="img_upload" alt="" width="640px" height="480px" />
                    </div>
                    <div style="">
                        <form method="post" enctype="multipart/form-data" class="img" action="upload.php">
                            <div>
                                <input type="file" accept="image/*" name="uploaded_img">
                                <input type="submit" name="submit" value="Upload"/>
                            </div>
                        </form>
                        <input type="button" id ="capture2" class="booth-capture-button" value = "Save photo" disabled>
                        <a href="index.php" style="text-decoration: none;">Camera</a>
                    </div>
                </div>
                <canvas id="canvas" height="480px" width="640px" style="display: none"></canvas>
            </div>
            <div class="column">
                <h1>Preview</h1>
                <?php
                include_once ('config/database.php');

                try {
                    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $query = $db->prepare("SELECT * FROM gallery WHERE user_id = ? ORDER BY date_created DESC");
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
                <form id="capture-form" name="capture-form" method="post" action="img.php">
                    <input type="hidden" name="img" id="picture" value=""/>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="js/upload.js"></script>
    <?php include('views/footer.php') ?>
    <?php } else {
    header("Location: login/login.php");
} ?>
</body>
</HTML>
