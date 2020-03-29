<?php

    //  Includes the value of $vars in the included file
    function includeWithVars($filename, $vars) {
        extract($vars);
        include $filename;
    }

    //  Utility function for fetching rows from the database
    //  if $isSingleRow == true, fetches only one row from the database
    function fetch($query, $isSingleRow = false)
    {
        $ret   = array();
        $conn  = OpenConnection();
        $stmt  = $conn->query($query);
        if ($stmt && $isSingleRow)
        {
            $ret = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        else if ($stmt && $stmt->rowCount() > 0)
        {
            $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        CloseConnection($conn);
        return $ret;
    }

    function get_csrf_token()
    {
        if (empty($_SESSION['token']))
            $_SESSION['token'] = bin2hex(random_bytes(32));

        return $_SESSION['token'];
    }

    function validate_csrf_token($token)
    {
        $t = $_SESSION['token'];
        unset($_SESSION['token']);

        if (hash_equals($t, $token))
            return true;

        error_log("Invalid CSRF token >> IP address: " . get_user_ip_addr());
        return false;
    }

    function get_user_ip_addr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            //  ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            //  ip from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $ip = $_SERVER['REMOTE_ADDR'];

        return $ip;
    }

?>