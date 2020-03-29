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
        <div class="navbar-menu-item">
            <?php
            if (basename($_SERVER['SCRIPT_FILENAME']) == "login.php") {
                echo "<a href='/pages/signup.php'>Don't have an account? Sign Up &rarr;</a>";
            } else {
                echo "<a href='/pages/login.php'>Already have an account? Log In &rarr;</a>";
            }
            ?>
        </div>
    </div>
</nav>