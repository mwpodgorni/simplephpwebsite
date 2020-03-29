<?php
    include "../includes/server.php";
    include "../includes/authorization/anonuserredirect.php";
?>

<!Doctype html>
<html>
    <head>
        <title>Wrangle | Create</title>
        <?php include "../includes/headmeta.php"; ?>
    </head>

    <body>
        <header>
            <?php include "../includes/authenticatedusernavbar.php"; ?>
        </header>

        <article class="main">
            <div class="create">
                <div class="create-header">
                    <h1>Create a wrangle</h1>
                </div>
                <div class="create-content">
                    <form method="POST" action="create.php" id="createForm">
                        <input type="hidden" name="token" value="<?php echo get_csrf_token(); ?>" />
                        <input type="text" name="topic" placeholder="Give your wrangle a good title" value="<?php echo $topic; ?>" maxlength="255" required/>
                        <textarea name="description" placeholder="Describe your wrangle topic in a few words (optional)" value="<?php echo $description; ?>"></textarea>
                        <div>
                            <input type="text" name="choiceA" placeholder="for" value="<?php echo $choiceA; ?>"maxlength="55" />
                            <input type="text" name="choiceB" placeholder="against" value="<?php echo $choiceB; ?>" maxlength="55"/>
                        </div>
                        <?php include "../includes/errors.php" ?>
                        <button type="submit" name="create">Create Wrangle</button>
                    </form>
                </div>
            </div>
        </article>
    </body>
</html>