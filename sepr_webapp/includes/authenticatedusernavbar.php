<?php

    $username = $_SESSION['user']['username'];
    $notifications = get_notifications($_SESSION['user']['user_id']);
?>

<header>
    <nav class="navbar">
        <div class="navbar-menu">
            <div class="navbar-menu-item">
                <a href="/index.php">
                    <img src="/assets/Icon.png" alt="Brand logo" />
                </a>
            </div>
            <div class="navbar-menu-item">
                <input type="search" placeholder="Search wrangles, people and more"/>
            </div>
            <?php if (basename($_SERVER['PHP_SELF']) != "create.php"): ?>
                <div class="navbar-menu-item" title="Create a wrangle">
                    <a href="/pages/create.php">
                        <span class="icon-item">
                            <i class="pe-7s-pen pe-fw"></i>
                        </span>
                    </a>
                </div>
            <?php endif ?>
            <div class="navbar-menu-item" onclick="toggleDropdown(event)">
                <a class="icon-item">
                    <i class="pe-7s-bell pe-fw"></i>
                </a>
                <div id="notification-dropdown" class="dropdown">
                    <div class="arrow notification-arrow"></div>
                    <div class="dropdown-menu notification-dropdown-menu">
                        <div class="dropdown-menu-header">
                            <span>Notifications</span>
                        </div>
                        <div class="dropdown-divider"></div>
                        <?php  if (count($notifications) > 0) : ?>
                            <?php foreach ($notifications as $item) : ?>
                                <div class="notification-dropdown-content">
                                    <p>
                                        <span><?php echo $item['username']; ?></span>
                                        <?php echo $item['verb']; ?>
                                    </p>
                                </div>
                            <?php endforeach ?>
                        <?php  else: ?>
                            <h1>No notifications yet!</h1>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="navbar-menu-item" onclick="toggleDropdown(event)">
                <a>
                    <img class="u-imgAvatar" src="<?php echo 'https://api.adorable.io/avatars/285/' . $username . '@adorable.png'; ?>" />
                </a>
                <div id="profile-dropdown" class="dropdown">
                    <div class="arrow profile-arrow"></div>
                    <div class="dropdown-menu profile-dropdown-menu">
                        <div class="dropdown-menu-header">
                            <a>Logged in as <strong><?php echo $username; ?></strong></a>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="/pages/profile.php">Your Profile</a>
                        <a href="/pages/profilesettings.php">Settings</a>
                        <div class="dropdown-divider"></div>
                        <a href="/pages/logout.php">Log out</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<script>
    function toggleDropdown(event)
    {
        //  there are only two nav items with a dropdown as the second child of the enclosing navbar-menu-item parent
        var dropdown = event.target.parentElement.nextElementSibling;

        //  toggle the other dropdown if visible
        document.querySelectorAll(".dropdown").forEach(el => {
            if (el != dropdown && el.classList.contains("active")) el.classList.toggle("active");
        })

        dropdown.classList.toggle("active");
    }
</script>
