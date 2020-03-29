<?php
    include "../includes/server.php";

    //  wrangle.id is passed in via a url parameter
    if (isset($_GET["id"]))
        $wrangle = get_wrangle($_GET["id"]);
?>

<!Doctype html>
<html>
    <head>
        <title>Wrangle | Detail</title>
        <?php include "../includes/headmeta.php"; ?>
    </head>

    <body onload="animateVotebars()">
    
        <script>
            function submitVoteAndResponseXHR(convenor, wrangleId) {
                var choiceA = document.getElementById('choiceA');
                var choiceB = document.getElementById('choiceB');
                if (!choiceA.checked && !choiceB.checked) return;

                var choiceVal = choiceA.checked ? 'A' : 'B';
                var responseText = document.getElementById('response').value;

                var xhr = new XMLHttpRequest();
                xhr.onload = function () {
                    if (xhr.status >= 200 && xhr.status < 300)
                        document.getElementById('voteAndRespond').remove();
                }

                var params = {
                    "convenor": convenor,
                    "wrangleId": wrangleId,
                    "choice": choiceVal,
                    "response": responseText
                }
                xhr.open('POST', `xhr.php`, true);
                xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                xhr.send(JSON.stringify(params));
            }
        </script>
        <header>
            <?php if (isset($_SESSION['is_authenticated'])): ?>
                <?php include "../includes/authenticatedusernavbar.php"; ?>
            <?php else: ?>
                <?php include "../includes/anonhomenavbar.php"; ?>
            <?php endif ?>
        </header>

        <article class="wrangle">
            <div class="wrangle-detail">
                <div class="wrangle-detail-header">
                    <h1><?php echo $wrangle["topic"]; ?></h1>
                    <span>started by <a href="#"><?php echo $wrangle["username"]; ?></a></span>
                    <hr>
                    <span class="u-textMuted">
                        <i class="pe-7s-users pe-2x pe-va"></i>
                        <?php echo $wrangle["followers_count"]; ?> followers
                    </span>
                    <span class="u-textMuted">
                        <i class="pe-7s-comment pe-2x pe-va"></i>
                        <!-- JS pluralization -->
                        <?php echo $wrangle["responses_count"]; ?>
                    </span>
                </div>
                <div class="wrangle-detail-content">
                    <p><?php echo $wrangle["description"]; ?></p>
                    <div class="wrangle-detail-votesbar u-xCenter">
                        <?php includeWithVars("votesbar.php", [
                            "votesA" => $wrangle['votesA'],
                            "votesB" => $wrangle['votesB']
                        ]); ?>
                    </div>
                    <?php if (isset($_SESSION['is_authenticated'])): ?>
                        <?php if ((!has_voted($wrangle['wrangle_id']) && ($wrangle['convenor'] != $_SESSION['user']['user_id']))): ?>
                            <!-- A user is only allowed to vote and comment once -->
                            <div id="voteAndRespond">
                                <div class="wrangle-detail-vote">
                                    <span class="u-iBlock"><?php echo $wrangle["choiceA"]; ?></span>
                                    <input type="radio" name="choice" id="choiceA" value="for" required />
                                    <input type="radio" name="choice" id="choiceB" value="against" required />
                                    <span class="u-iBlock"><?php echo $wrangle["choiceB"]; ?></span>
                                </div>
                                <div class="wrangle-detail-postcomment">
                                    <img class="u-imgAvatar u-iBlock" src="<?php echo 'https://api.adorable.io/avatars/285/' . $_SESSION['user']['username'] . '@adorable.png'; ?>" />
                                    <textarea class="u-iBlock" id="response"></textarea>
                                    <button class="u-block u-xCenter"
                                            type="submit"
                                            onclick="submitVoteAndResponseXHR('<?php echo $wrangle['convenor']; ?>', '<?php echo $wrangle['wrangle_id']; ?>')">Respond</button>
                                </div>
                            </div>
                        <?php endif ?>
                    <?php else: ?>
                        <p><i><a href="signup.php">create an account or login to partipate in this wrangle</a></i></p>
                    <?php endif ?>
                    <hr width="1" size="30">

                    <div class="wrangle-detail-comments u-xCenter">
                        <h2>Responses</h2>
                        <?php include "../includes/comment.php"; ?>
                    </div>
                </div>
                <div class="wrangle-detail-footer">
                </div>
            </div>
        </article>
    </body>
</html>


