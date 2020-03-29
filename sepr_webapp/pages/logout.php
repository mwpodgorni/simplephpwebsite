<?php
    session_start();

    unset($_SESSION['is_authenticated']);
    unset($_SESSION["user"]);

    header("location: /pages/login.php");
    session_destroy();
?>
