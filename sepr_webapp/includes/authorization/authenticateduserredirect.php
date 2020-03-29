<?php

    //  redirect user to home page if already logged in
    if (isset($_SESSION['is_authenticated']))
        header('location: /index.php');
?>
