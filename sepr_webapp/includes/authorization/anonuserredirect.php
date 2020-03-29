<?php

    //  redirect user to home page if not logged in
    if (!isset($_SESSION['is_authenticated']))
        header('location: /pages/login.php');
?>
