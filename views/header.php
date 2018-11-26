<?php if (isset($_SESSION['id'])) { ?>
    <div id="navbar">
        <a href="#" id="logo">Camagru</a>
        <div id="navbar-right">
            <a href="index.php">Home</a>
            <a href="gallery.php">Gallery</a>
            <a href="profile.php">Profile</a>
            <a href="preference.php">Preference</a>
            <a href="logout.php">Logout</a>

        </div>
    </div>
<?php } else { ?>
    <div id="navbar">
        <a href="index.php" id="logo">Camagru</a>
        <div id="navbar-right">
            <a href="../gall.php">Gallery</a>
        </div>
    </div>
<?php } ?>