<?php
    include "../includes/server.php";
    include "../includes/authorization/anonuserredirect.php";
?>

<!Doctype html>
<html>
    <head>
        <title>Wrangle | Account</title>
        <?php include "../includes/headmeta.php"; ?>
    </head>

    <body>
        <script>
            function deleteAccount(userId) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', `xhr.php?userId=${userId}`);
                xhr.send();
            }
        </script>
        <header>
            <?php include "../includes/authenticatedusernavbar.php"; ?>
        </header>

        <article class="settings">
            <div class="settings-sidebar">
                <a href="profilesettings.php">Profile</a>
                <a href="accountsettings.php" class="active">Account</a>
            </div>
            <div class="settings-main">
                <h1 class="u-textMuted">Change username</h1>
                <form method="POST" action="accountsettings.php" id="accountForm">

                    <input type="hidden" name="token" value="<?php echo get_csrf_token(); ?>" />
                    <label for="new-username">New Username</label>
                    <input type="text" name="new-username" id="new-username" />
                    <?php include "../includes/errors.php" ?>

                    <button type="submit" form="accountForm" name="account">Change username</button>

                    <h1 class="u-textMuted">Delete account</h1>
                    <p>Once you delete your account, there is no going back. Please be certain!</p>
                    <button type="button" onclick="deleteAccount('<?php echo $_SESSION['user']['user_id']; ?>')">Delete your account</button>
                </form>
            </div>
        </article>
    </body>
</html>
