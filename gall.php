<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <title>Gallery</title>
</head>
<body>
    <div id="navbar">
        <a href="#" id="logo">Camagru</a>
        <div id="navbar-right">
            <a href="login/login.php">Login</a>
        </div>
    </div>
    <!-- <?php //include './views/header.php'; ?> -->
    <?php
            include_once ('config/database.php');

            try {
                $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//                $display="";
                $per_page = 5;
                if (isset($_GET['page'])) {
                    $curpage = $_GET['page'];
                } else {
                    $curpage = 1;
                }

                //Page will start from 0 and Multiple by per page
                $start = ($curpage - 1) * $per_page;

                $stmt = $db->prepare("SELECT * FROM gallery");
                $stmt->execute();
                $total_res = $stmt->rowCount();
                $endpage = ceil($total_res/$per_page);
                $start_page = 1;
                $next_page = $curpage + 1;
                $previous_page = $curpage - 1;

                $query = $db->prepare("SELECT * FROM gallery ORDER BY date_created DESC LIMIT $start, $per_page");
                // $value = array($_SESSION['id']);
                $query->execute();
                $result = $query->fetchAll();
                if (!empty($result)) {
                    foreach ($result as $v) { 
                        $smtp = $db->prepare("SELECT * FROM likes WHERE gallery_id = ?");
                        $smtp->execute(array($v['id']));
                        $count = $smtp->rowCount();

                        $smtm = $db->prepare("SELECT * FROM comment WHERE gallery_id = ?");
                        $smtm->execute(array($v['id']));
                        $count1 = $smtm->rowCount();
                        ?>
                            <img class="img" src="<?php echo "images/".$v['img']; ?>" />
                            <a class="btn" href="delete.php?id=<?php echo $v['id']; ?>">delete</a>
                            <a class="btn1" href="like.php?id=<?php echo $v['id']; ?>">like <?php echo $count; ?></a>
                            <form method="post" action="comment.php?id=<?php echo $v['id']; ?>; ?>">
                                <textarea name="msg" cols="" rows=""></textarea>
                                <button class="btn" type="submit" name="submit">comment</button>
                            </form>
                    <?php } ?>
                    <nav style="text-align: center">
                        <ul class="pagination">
                            <?php if($curpage != $start_page){ ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $start_page ?>" tabindex="-1" aria-label="Previous">
                                        <span>First</span>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if($curpage >= 2){ ?>
                                <li class="page-item"><a class="page-link" href="?page=<?php echo $previous_page ?>"><span aria-hidden="true">&laquo;</span></a></li>
                                <li class="page-item"><a class="page-link" href="?page=<?php echo $previous_page ?>"><?php echo $previous_page ?></a></li>
                            <?php } ?>

                            <li class="page-item active"><a class="page-link" href="?page=<?php echo $curpage ?>"><?php echo $curpage ?> </a></li>

                            <?php if($curpage != $endpage){ ?>
                                <li class="page-item"><a class="page-link" href="?page=<?php echo $next_page ?>"><?php echo $next_page ?></a></li>
                                <li class="page-item"><a class="page-link" href="?page=<?php echo $next_page?>"><span aria-hidden="true">&raquo;</span></a></li>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $endpage ?>" aria-label="Next">
                                        <span>Last</span>
                                    </a>
                                </li>
                                <li class="page-item"><a style="color: black" class="page-link">Showing <?php if ($curpage == 1) {echo $curpage;} else echo ($per_page * $curpage) - 9; ?> to <?php echo $per_page * $curpage; ?> of <?php echo $total_res; ?> entries</a></li>
                            <?php } ?>

                            <?php if ($curpage == $endpage) : ?>
                                <li class="page-item"><a class="page-link">Showing <?php echo ($per_page * $curpage) - 4; ?> to <?php echo $total_res; ?> of <?php echo $total_res; ?> entries</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php
                } else { ?>
                    <div style="width: 50%; background-color:#66ffff; color:white; font-size:50px; margin:auto; text-align:center; position:relative; top:20px"><strong>No Pictures!</strong></div>
            <?php }

            } catch (PDOException $e) {
                    return $e->getMessage();
            }
            
                $db = null;
            ?>
    <?php include './views/footer.php'; ?>
</body>
</html>