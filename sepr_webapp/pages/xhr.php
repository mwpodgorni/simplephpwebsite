<?php

    include "../includes/server.php";
    include "../includes/notify.php";

    include_once "../objects/notificationtypes.php";

    //  delete account
    if (isset($_POST['userId']))
    {
        $conn   = OpenConnection();

        $query = "DELETE from users WHERE id = ?";
        $stmt =  $conn->prepare($query);
        $stmt->execute([$_POST['userId']]);
        
        unset($_SESSION['is_authenticated']);
        unset($_SESSION["user"]);

        header('location: /index.php');
        session_destroy();
        
        CloseConnection($conn);
        exit();
    }

    //  follow wrangle
    if (isset($_GET["followerId"]) && isset($_GET["wrangleId"]))
    {
        $conn = OpenConnection();
        $follower_id = $_GET['followerId'];
        $wrangle_id  = $_GET['wrangleId'];

        $query  = "INSERT INTO followers (follower, wrangle) VALUES (:follower, :wrangle)";
        $stmt   = $conn->prepare($query);
        $stmt->execute(['follower' => $follower_id, 'wrangle' => $wrangle_id]);

        if ($stmt)
        {
            if (notify($conn, $follower_id, NotificationType::Follow));
                http_response_code(200);
        }
        else
            http_response_code(400);

        CloseConnection($conn);
        exit();
    }

    //  vote and respond on wrangle
    $data = json_decode(file_get_contents('php://input'));
    if (!(empty($data->convenor)     ||
         (empty($data->wrangleId)) ||
         (empty($data->choice))
    ))
    {
        $conn = OpenConnection();

        //  sanitize
        $userId    = $_SESSION['user']['user_id'];
        $convenor  = htmlspecialchars(strip_tags($data->convenor));
        $wrangleId = htmlspecialchars(strip_tags($data->wrangleId));
        $choice    = htmlspecialchars(strip_tags($data->choice));
        if ($data->response)
            $response  = htmlspecialchars(strip_tags($data->response));

        //  insert the vote...
        $query = "INSERT INTO votes (choice, wrangle, voter) VALUES (:choice, :wrangle, :user)";
        $stmt   = $conn->prepare($query);
        $stmt->execute(['user' => $userId, 'wrangle' => $wrangleId, 'choice' => $choice]);
        if (!$stmt)
        {
            http_response_code(400);
            CloseConnection($conn);
            exit(); // short-circuit the script
        }
        else
        {
            if (!notify($conn, $convenor, NotificationType::Vote))
            {
                CloseConnection($conn);
                exit();
            }
        }

        //  ...then insert response
        if ($data->response)
        {
            $query = "INSERT INTO responses (`user`, wrangle, text) VALUES (:userId, :wrangleId, :responseText)";
            $stmt   = $conn->prepare($query);
            $stmt->execute(['userId' => $userId, 'wrangleId' => $wrangleId, 'responseText' => $response]);

            if ($stmt)
            {
                if (notify($conn, $convenor, NotificationType::Response))
                    http_response_code(201);
                else
                    http_response_code(400);
            }
            else
                http_response_code(400);
        }

        CloseConnection($conn);
        exit();
    }

?>