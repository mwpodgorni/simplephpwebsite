<?php
    include "connection.php";
    include "utils.php";

    //  Init variables
    $errors         = array();
    $username       = "";
    $email          = "";
    $username_email = "";
    $topic          = "";
    $description    = "";
    $choiceA        = "";
    $choiceB        = "";

    $regex          = '/^[a-z0-9][a-z0-9_]*[a-z0-9]$/';

    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        !empty($_POST['token']) &&
        validate_csrf_token($_POST['token']))
    {

        //  Register user
        if (isset($_POST['signup'])) {
            $conn = OpenConnection();
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            //  input validation
            if (preg_match($regex, $username) == 0)
                array_push($errors, "Username must start with a letter and only contain numbers, letters and underscores");

            // validate password length
            if (strlen($password) < 8) array_push($errors, "Password must be at least 8 characters");

            // register the user if there are no errors
            if (count($errors) == 0) {
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND username = ?");
                $stmt->execute([$email, $username]);

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // if user exists, populate errors array
                if ($user) {
                    if ($user['username'] === $username) array_push($errors, "Username taken");
                    if ($user['email'] === $email) array_push($errors, "Email already exists");
                } else {
                    $password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                    $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password]);

                    unset($_SESSION['token']);
                    header("location: /pages/login.php");
                }
            }

            CloseConnection($conn);
        }


        //  Login user
        if (isset($_POST['login'])) {
            $conn = OpenConnection();
            $username_email = $_POST['username_email'];
            $password = $_POST['password'];

            $query = "SELECT * FROM users WHERE email = ? OR username = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$username_email, $username_email]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['is_authenticated'] = true;
                $_SESSION['user'] = [
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'name' => $user['name'],
                    'bio' => $user['bio'],
                    'location' => $user['location']
                ];

                unset($_SESSION['token']);
                header('location: /index.php');
            } else {
                array_push($errors, "Your credentials could not be authenticated");
            }

            CloseConnection($conn);
        }

        //  Create wrangle
        if (isset($_POST['create']) && isset($_SESSION['is_authenticated'])) {
            $conn = OpenConnection();
            $topic = $_POST['topic'];
            $description = $_POST['description'];
            $choiceA = $_POST['choiceA'];
            $choiceB = $_POST['choiceB'];
            $userid = $_SESSION['user']['user_id'];

            if (empty($choiceA) || empty($choiceB)) {
                $query = "INSERT INTO wrangles (convenor, topic, description) 
                          VALUES (:convenor, :topic, :description)";
                $stmt = $conn->prepare($query);
                $stmt->execute(['convenor' => $userid, 'topic' => $topic, 'description' => $description]);
            } else {
                $query = "INSERT INTO wrangles (convenor, topic, description, choiceA, choiceB) 
                          VALUES (:convenor, :topic, :description, :choiceA, :choiceB)";
                $stmt = $conn->prepare($query);
                $stmt->execute(['convenor' => $userid, 'topic' => $topic, 'description' => $description, 'choiceA' => $choiceA, 'choiceB' => $choiceB]);
            }

            if ($stmt) {
                unset($_SESSION['token']);
                header('location: /index.php');
            } else {
                array_push($errors, "An error occurred");
            }

            CloseConnection($conn);
        }

        //update username
        if (isset($_POST['account'])) {
            $conn = OpenConnection();
            $newusername = $_POST['new-username'];
            $userid = $_SESSION['user']['user_id'];

            //  input validation
            if (preg_match($regex, $newusername) == 0)
                array_push($errors, "Username must start with a letter and only contain numbers, letters and underscores");

            // register the user if there are no errors
            if (count($errors) == 0) {
                $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$newusername]);

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // if user exists, populate errors array
                if ($user) {
                    if ($user['username'] === $newusername) array_push($errors, "Username taken");
                } else {
                    $query = "UPDATE users SET username = ? where id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$newusername, $userid]);

                    if ($stmt) {
                        //  update session with new username value
                        $_SESSION['user']['username'] = $newusername;
                        $errors = array();
                        unset($_SESSION['token']);
                    } else
                        array_push($errors, "An error occured.");
                }
            }

            CloseConnection($conn);
        }

        // Update profile
        if (isset($_POST['profile'])) {
            $conn = OpenConnection();
            $name = $_POST['name'];
            $bio = $_POST['bio'];
            $location = $_POST['location'];
            $userid = $_SESSION['user']['user_id'];

            $query = "UPDATE users SET name = ?, bio = ?, location = ?
                        WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$name, $bio, $location, $userid]);

            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['bio'] = $bio;
            $_SESSION['user']['location'] = $location;

            unset($_SESSION['token']);
            CloseConnection($conn);
        }
    }
    else
    {
        unset($_SESSION['token']);
    }

    //  Sidebar
    function get_most_followed_wrangles()
    {
        $query = "SELECT id, 
                         topic,
                         (SELECT COUNT(*) FROM followers WHERE wrangle = w.id) as followers_count
                  FROM wrangles w
                  ORDER BY followers_count DESC
                  LIMIT 3";

        return fetch($query);
    }

    function get_trending_wrangles()
    {
        //  Linear regresion algorithm will be overkill
        //  so a simple algorithm using time delta is utilised
        //  We use a 72-hour time frame
        $query = "SELECT id, 
                         topic,
                         (SELECT COUNT(*) FROM responses r WHERE r.wrangle = w.id) as responses_count,
                         (SELECT COUNT(*) FROM followers WHERE wrangle = w.id) as followers_count,
                         (SELECT COUNT(*) FROM votes WHERE wrangle = w.id) as votes_count
                  FROM wrangles w
                  WHERE created > NOW() - INTERVAL 1 MONTH
                  ORDER BY (followers_count + responses_count + votes_count) DESC
                  LIMIT 3";

        return fetch($query);
    }

    //  Feed
    function populate_feed()
    {
        $query = "SELECT *, 
                         w.id as wrangle_id, 
                         u.id as user_id,
                         (SELECT COUNT(*) FROM responses r WHERE w.id = r.wrangle) as responses_count,
                         (SELECT COUNT(*) FROM followers WHERE wrangle = w.id) as followers_count,
                         (SELECT COUNT(*) FROM votes WHERE wrangle = w.id AND choice = 'A') as votesA,
                         (SELECT COUNT(*) FROM votes WHERE wrangle = w.id AND choice = 'B') as votesB
                  FROM wrangles w JOIN users u ON (w.convenor = u.id) 
                  LIMIT 10";

        return fetch($query);
    }

    //  User feed
    function populate_user_feed($userid)
    {
        $query = "SELECT *, 
                         w.id as wrangle_id, 
                         u.id as user_id, 
                         (SELECT COUNT(*) FROM responses r WHERE w.id = r.wrangle) as responses_count,
                         (SELECT COUNT(*) FROM followers WHERE wrangle = w.id) as followers_count,
                         (SELECT COUNT(*) FROM votes WHERE wrangle = w.id AND choice = 'A') as votesA,
                         (SELECT COUNT(*) FROM votes WHERE wrangle = w.id AND choice = 'B') as votesB
                  FROM wrangles w JOIN users u ON (w.convenor = u.id) 
                  WHERE u.id = '$userid' 
                  LIMIT 10";

        return fetch($query);
    }

    //  User followed wrangles
    function get_followed_wrangles($userid)
    {
        $query = "SELECT *,  
                         w.id as wrangle_id, 
                         u.id as user_id,
                         (SELECT COUNT(*) FROM responses r WHERE w.id = r.wrangle) as responses_count,
                         (SELECT COUNT(*) FROM followers WHERE wrangle = w.id) as followers_count,
                         (SELECT COUNT(*) FROM votes WHERE wrangle = w.id AND choice = 'A') as votesA,
                         (SELECT COUNT(*) FROM votes WHERE wrangle = w.id AND choice = 'B') as votesB
                  FROM wrangles w JOIN users u ON (w.convenor = u.id)
                                  JOIN followers f ON (f.wrangle = w.id)
                  WHERE f.follower = '$userid' 
                  LIMIT 10";

        return fetch($query);
    }

    //  Wrangle detail
    function get_wrangle($id)
    {
        $query = "SELECT *, 
                         w.id as wrangle_id,
                         (SELECT COUNT(*) FROM responses r WHERE w.id = r.wrangle) as responses_count,
                         (SELECT COUNT(*) FROM followers WHERE wrangle = w.id) as followers_count,
                         (SELECT COUNT(*) FROM votes WHERE wrangle = w.id AND choice = 'A') as votesA,
                         (SELECT COUNT(*) FROM votes WHERE wrangle = w.id AND choice = 'B') as votesB
                  FROM wrangles w JOIN users u ON (w.convenor = u.id)
                  WHERE w.id = '$id'";

        return fetch($query, true);
    }

    //  Wrangle responses
    function populate_responses($wrangle_id)
    {
        $query = "SELECT username, text, r.created as response_created
                  FROM responses r JOIN users u ON (r.user = u.id) WHERE r.wrangle = '$wrangle_id'";

        return fetch($query);
    }

    //  Returns true if the logged in user is following the given wrangle
    function is_following($wrangle_id)
    {
        $conn  = OpenConnection();
        $query = "SELECT * FROM followers WHERE follower = " . $_SESSION['user']['user_id'] . " AND wrangle = '$wrangle_id'";
        $stmt  = $conn->query($query);

        if ($stmt->rowCount() > 0)
            return true;

        return false;
    }

    //  Returns true if the logged in user has voted on the given wrangle
    function has_voted($wrangle_id)
    {
        $conn  = OpenConnection();
        $query = "SELECT * FROM votes WHERE voter = " . $_SESSION['user']['user_id'] . " AND wrangle = '$wrangle_id'";
        $stmt  = $conn->query($query);

        if ($stmt->rowCount() > 0)
            return true;

        return false;
    }

    //  Returns all notifications for the logged in user
    function get_notifications($user_id)
    {
        $query = "SELECT *, tu.username as to_username, fu.username as from_username
                  FROM notifications n 
                  JOIN users tu ON (n.to_user = tu.id)
                  JOIN users fu ON (n.from_user = fu.id)
                  WHERE to_user = '$user_id' 
                  AND at > NOW() - INTERVAL 24 HOUR 
                  LIMIT 10";
        return fetch($query);
    }

?>
