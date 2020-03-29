<?php
    include "../includes/server.php";
    include "../includes/authorization/anonuserredirect.php";

    $username = $_SESSION['user']['username'];
?>

<!Doctype html>
<html>
    <head>
        <title>Wrangle | <?php echo $username ?></title>
        <?php include "../includes/headmeta.php"; ?>
    </head>

    <body>
        <header>
            <?php include "../includes/authenticatedusernavbar.php"; ?>
        </header>
        <script>
            function openTab(evt, tab) {
                // Declare all variables
                var i, tabcontent, tablinks;

                // Get all elements with class="tabcontent" and hide them
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }

                // Get all elements with class="tablinks" and remove the class "active"
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }

                // Show the current tab, and add an "active" class to the button that opened the tab
                document.getElementById(tab).style.display = "block";
                evt.currentTarget.className += " active";
            }
        </script>

        <article class="profile">
            <div class="profile-sidebar">
                <img src="<?php echo 'https://api.adorable.io/avatars/285/' . $username . '@adorable.png'; ?>" />

                <?php if (isset($username)): ?>
                    <h3><?php echo $username; ?></h3>
                <?php endif ?>
                <?php if (isset($_SESSION['user']['bio'])): ?>
                    <p><?php echo $_SESSION['user']['bio']; ?></p>
                <?php endif ?>
                <?php if (isset($_SESSION['user']['location'])): ?>
                    <span class="u-iBlock"><i class="pe-7s-map-marker pe-fw"></i><?php echo $_SESSION['user']['location']; ?></span>
                <?php endif ?>
                <button type="button" onclick="window.location.href = 'profilesettings.php'">Edit Profile</button>
            </div>

            <div class="profile-main">
                <div class="profile-nav">
                    <ul>
                        <li class="tablinks active" onclick="openTab(event, 'Your Wrangles')">Your Wrangles</li>
                        <li class="tablinks" onclick="openTab(event, 'Followed Wrangles')">Followed Wrangles</li>
                    </ul>
                </div>
                <div id="Your Wrangles" class="tabcontent">   
                    <article class="profile-article">
                        <?php includeWithVars("../includes/feed.php", [
                                "isOwnWrangles" => true // get own user wrangles
                        ]); ?>
                    </article>
                </div>
                <div id="Followed Wrangles" class="tabcontent" style="display: none;">   
                    <article class="profile-article">
                        <?php includeWithVars("../includes/feed.php", [
                            "isOwnWrangles" => false // get followed wrangles
                        ]); ?>
                    </article>
                </div>
            </div>
        </article>
    </body>
</html>