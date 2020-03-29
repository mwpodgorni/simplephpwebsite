<?php
    include "includes/server.php";
?>

<!Doctype html>
<html>
    <head>
        <title>Wrangle | Home</title>
        <?php include "includes/headmeta.php"; ?>
    </head>

    <body>
        <?php if (isset($_SESSION['is_authenticated'])): ?>
            <?php include "includes/authenticatedusernavbar.php"; ?>
        <?php else: ?>
            <?php include "includes/anonhomenavbar.php"; ?>
        <?php endif ?>

        <article class="home" id="home">
            <?php includeWithVars( "includes/feed.php", array(
                    "isOwnWrangles" => false
            ))?>
        </article>

        <?php include "includes/sidebar.php" ?>
    </body>
</html>

