<?php $dateObj = new \DateTime($article["datetime"]); ?>
<dl style="padding: 10px 0 20px 0">
    <dt><?php echo $article["title"]; ?></dt>
    <dd>
        <?php echo substr($article["content"], 0, 150);?>
        <a href="index.php?page=articles&action=viewarticle&id=<?=$article['id']?>">view article</a>
    </dd>
    <dd><?php echo $dateObj->format('d F, Y, H:i');?></dd>
</dl>