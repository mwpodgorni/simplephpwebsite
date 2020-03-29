<?php
    include "../includes/server.php";
    include "../includes/authorization/authenticateduserredirect.php";
?>

<!Doctype html>
<html>
    <head>
        <title>Wrangle | Log In</title>
        <?php include "../includes/headmeta.php"; ?>
    </head>

    <body>
        <header>
            <?php include "../includes/anonusernavbar.php"; ?>
        </header>

        <article class="auth">
            <div class="auth-card">
                <div class="auth-card-header">
                    <img src="/assets/Logo.png" />
                    <p>Log into your account</p>
                </div>
                <div class="auth-card-content">
                    <form method="POST" action="login.php" id="loginForm">
                        <input type="hidden" name="token" value="<?php echo get_csrf_token(); ?>" />
                        <input type="text" name="username_email" placeholder="username or email" value="<?php echo $username_email; ?>" required /><br>
                        <input type="password" name="password" placeholder="password" required />
                    </form>
                    <?php include "../includes/errors.php" ?>
                    <button type="submit" form="loginForm" name="login">Log In</button>
                    <a href="/pages/resetpassword.php">Forgot password?</a>
                </div>
            </div>
        </article>
    </body>
</html>