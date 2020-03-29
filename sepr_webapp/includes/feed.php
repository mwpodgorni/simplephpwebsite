<?php

    if (basename($_SERVER["SCRIPT_NAME"]) == "index.php")
    {
        $feed = populate_feed();
    }
    else
    {
        //  this test is done on a variable included via a utility function
        if ($isOwnWrangles)
            $feed = populate_user_feed($_SESSION['user']['user_id']);
        else
            $feed = get_followed_wrangles($_SESSION['user']['user_id']);
            
    }
?>

<?php  if (count($feed) > 0) : ?>
    <?php foreach ($feed as $item) : ?>
        <div class="feed-card">
            <div class="feed-card-header">
                <a href="#" class="feed-card-avatar">
                    <img class="u-imgAvatar" src="<?php echo 'https://api.adorable.io/avatars/285/' . $item['username'] . '@adorable.png'; ?>"/>
                </a>
                <div class="feed-card-meta">
                    <span><a href="#"><?php echo $item['username']; ?></a></span>
                    <span class="middot">&middot;</span>
                    <span><?php echo date('l jS, F Y', strtotime($item["created"])); ?></span>
                </div>
            </div>
            <div class="feed-card-content">
                <h2><?php echo $item['topic']; ?></h2>
                <p><?php echo $item['description']; ?></p>
                <div class="feed-card-votesbar">
                    <?php includeWithVars("votesbar.php", [
                        "votesA" => $item['votesA'],
                        "votesB" => $item['votesB']
                    ]); ?>
                </div>
                <div class="feed-card-footer">
                    <div class="feed-card-stats">
                        <i class="pe-7s-comment pe-fw" title="Comments"></i>
                        <span><?php echo $item['responses_count']; ?></span>
                        <i class="pe-7s-users pe-fw" title="Followers"></i>
                        <span><?php echo $item['followers_count']; ?></span>
                    </div>

                    <div class="feed-card-actions">
                        <!-- show the 'follow wrangle' button only if there is an authenticated user and the wrangle is not owned -->
                        <?php if (isset($_SESSION['is_authenticated']) && ($item['user_id'] != $_SESSION['user']['user_id'])): ?>
                            <?php if (is_following($item['wrangle_id'])): ?>
                                <i class="pe-7s-check pe-fw" title="Following"></i>
                            <?php else: ?>
                                <i class="pe-7s-look pe-fw"
                                   title="Follow"
                                   onclick="followWrangleXHR(this, '<?php echo $_SESSION['user']['user_id']; ?>', '<?php echo $item['wrangle_id']; ?>')"></i>
                            <?php endif ?>
                        <?php endif ?>
                        <a href="<?php echo "/pages/wrangledetail.php?id=" . $item['wrangle_id']; ?>">
                            <i class="pe-7s-right-arrow pe-fw" title="Go to wrangle"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
<?php  else: ?>
    <h1>No wrangles yet!</h1>
<?php endif ?>

<script>
    window.onload = animateVotebars();
    window.addEventListener('resize', animateVotebars, false);

    function followWrangleXHR(elem, followerId, wrangleId) {
        var xhr = new XMLHttpRequest();
        var sub = document.createElement('i');
        sub.setAttribute('class', 'pe-7s-check pe-fw');
        sub.setAttribute('title', 'Following');

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300)
                elem.replaceWith(sub);
        }

        xhr.open('POST', `pages/xhr.php?followerId=${followerId}&wrangleId=${wrangleId}`);
        xhr.send();
    }
</script>