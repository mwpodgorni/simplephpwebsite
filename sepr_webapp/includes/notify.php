<?php

    include_once "../objects/notificationtypes.php";
    include_once "../objects/verbs.php";

    //  sends a notification to the specified user
    //  $type is one of:
    //      - 'comment'
    //      - 'response'
    //      - 'vote'
    function notify($conn, $convenor, $type)
    {
        $verb = '';

        //  the verb depends on the type
        switch ($type) {
            case NotificationType::Follow:
                $verb = Verbs::Follow;
                break;
            case NotificationType::Response:
                $verb = Verbs::Response;
                break;
            case NotificationType::Vote:
                $verb = Verbs::Vote;
                break;
        }

        $query  = "INSERT INTO notifications (type, to_user, from_user, verb) VALUES (:type, :to, :from, :verb)";
        $stmt   = $conn->prepare($query);
        $stmt->execute(['type' => $type, 'to' => $convenor, 'from' => $_SESSION['user']['user_id'], 'verb' => $verb]);

        if ($stmt)
            return true;

        return false;
    }

?>