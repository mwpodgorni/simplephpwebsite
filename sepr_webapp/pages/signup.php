<?php
    include "../includes/server.php";
    include "../includes/authorization/authenticateduserredirect.php";
?>

<!Doctype html>
<html>
    <head>
        <title>Wrangle | Sign Up</title>
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
                    <p>Create an account</p>
                </div>
                <div class="auth-card-content">
                    <form method="POST" action="signup.php" id="signupForm">
                        <input type="hidden" name="token" value="<?php echo get_csrf_token(); ?>" />
                        <input type="text" name="username" placeholder="username" value="<?php echo $username; ?>" maxlength="100" required /><br>
                        <input type="email" name="email" placeholder="email" value="<?php echo $email; ?>" maxlength="100" required /><br>
                        <input type="password" name="password" placeholder="password" maxlength="100" required />
                    </form>
                    <?php include "../includes/errors.php" ?>
                    <button type="submit" form="signupForm" name="signup">Sign Up</button>
                </div>
            </div>
        </article>
    </body>
</html>