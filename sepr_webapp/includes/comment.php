<?php
    $responses = populate_responses($_GET["id"]);
?>

<?php  if (count($responses) > 0) : ?>
    <?php foreach ($responses as $response) : ?>
        <div class="comment">
            <a href="#" class="u-iBlock">
                <img class="u-imgAvatar" src="<?php echo 'https://api.adorable.io/avatars/285/' . $response['username'] . '@adorable.png'; ?>" />
            </a>
            <div class="u-iBlock">
                <div class="comment-meta">
                    <span><a href="#"><?php echo $response["username"]; ?></a></span>
                    <span class="middot">&middot;</span>
                    <span><?php echo date('l jS , F Y', strtotime($response["response_created"])); ?>
                </div>
                <div class="comment-body">
                    <?php echo $response["text"]; ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
<?php  else: ?>
    <p>No responses yet!</p>
<?php endif ?>