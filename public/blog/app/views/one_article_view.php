<main>
<?php if($article !== null) { ?>

    <h3><?=$article['title']?></h3>
    <p><?=$article['content']?></p>
    <small><?php $dateObj = new \DateTime($article["datetime"]); ?>
        <?php echo $dateObj->format('d F, Y, H:i');?></small>

    <br><br>
    <h4>Comments - <a href="javascript:;" onclick="$('#commentForm').slideToggle()">add new comment</a></h4>

    <form id="commentForm" style="display: none" class="new-article" action="index.php?page=articles&action=addcomment&article_id=<?=$article['id']?>" method="POST">
        <div class="input-text">
            <label for="title">Name</label>
            <input type="text" name="name"/>
        </div>
        <div class="textarea">
            <label>Comment</label>
            <textarea name="content"></textarea>
        </div>
        <button id="add-article" type="submit">Add</button>
    </form>
    <hr>

    <?php if (count($comments) > 0) { ?>
        <div class="article-comments">
        <?php foreach ($comments as $comment) { ?>
            <?php $dateObj = new \DateTime($comment["datetime"]); ?>
            <dl style="padding: 10px 0 20px 0">
                <dt>By: <?php echo $comment["author_name"]; ?> @ <?php echo $dateObj->format('d F, Y, H:i');?></dt>
                <dd><?php echo $comment["content"];?></dd>
            </dl>
        <?php } ?>
        </div>
    <?php } else { ?>
    <p>No comments yet.</p>
    <?php } ?>



<?php } else { ?>
    <p>No article found!</p>
<?php } ?>
</main>