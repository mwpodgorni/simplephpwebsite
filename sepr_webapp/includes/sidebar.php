<?php
    $most_followed = get_most_followed_wrangles();
    $trending      = get_trending_wrangles();
?>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-main">
        <div class="sidebar-segment">
            <h4>Most followed wrangles</h4>
            <hr>
            <?php foreach ($most_followed as $item) : ?>
                <div onclick="window.location.href = '/pages/wrangledetail.php?id=<?php echo $item['id']; ?>'">
                    <p class="u-iBlock"><?php echo $item['topic']; ?></p>
                    <span><?php echo $item['followers_count']; ?> follower(s)</span>
                </div>
            <?php endforeach ?>
        </div>
        <div class="sidebar-segment">
            <h4>Trending wrangles</h4>
            <hr>
            <?php foreach ($trending as $item) : ?>
                <div onclick="window.location.href = '/pages/wrangledetail.php?id=<?php echo $item['id']; ?>'">
                    <p><?php echo $item['topic']; ?></p>
                    <div>
                        <span><?php echo $item['followers_count']; ?> follower(s)</span>
                        <span><?php echo $item['responses_count']; ?> response(s)</span>
                        <span><?php echo $item['votes_count']; ?> vote(s)</span>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    <footer class="sidebar-footer">
        <span onclick="hideSidebar()" title="Hide sidebar">Hide</span>
        <span onclick="window.location.reload(true)" title="Refresh sidebar">Refresh</span>
    </footer>
</aside>

<script>
    function hideSidebar() {
        document.getElementById('sidebar').remove();
        document.getElementById('home').style.margin = '0 auto';
    }
</script>
