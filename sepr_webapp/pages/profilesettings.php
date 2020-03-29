<?php
    include "../includes/server.php";
    include "../includes/authorization/anonuserredirect.php";
?>

<!Doctype html>
<html>
    <head>
        <title>Wrangle | Profile</title>
        <?php include "../includes/headmeta.php"; ?>
    </head>

    <body>
        <header>
            <?php include "../includes/authenticatedusernavbar.php"; ?>
        </header>

        <article class="settings">
            <div class="settings-sidebar">
                <a href="profilesettings.php" class="active">Profile</a>
                <a href="accountsettings.php">Account</a>
            </div>
            <div class="settings-main">
                <h1 class="u-textMuted">Public Profile</h1>
                <form method="POST" action="profile.php" id="profileForm">
                    <input type="hidden" name="token" value="<?php echo get_csrf_token(); ?>" />

                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="<?php echo $_SESSION['user']['name']; ?>"/>
                    <br>

                    <label for="bio">Bio</label>
                    <textarea name="bio" id="bio"><?php echo $_SESSION['user']['bio']; ?></textarea>
                    <br>

                    <label for="location">Location</label>
                    <input type="text" name="location" id="location" value="<?php echo $_SESSION['user']['location']; ?>" />
                    <br>
                    <button type="submit" form="profileForm" name="profile">Update profile</button>
                </form>
            </div>
            <div class="photo-box">
                <h4 class="u-textMuted">Profile Picture</h4>
                <!-- TODO: JS edit dropdown - change photo, remove photo -->
                <img src="https://api.adorable.io/avatars/285/abott@adorable.png" alt="Change profile picture">
            </div>
        </article>
    </body>
</html>