<?php
/**
 * Created by PhpStorm.
 * User: NhatLe
 * Date: 22-Aug-18
 * Time: 17:01
 */
?>
<?php
$catID = get_the_category()[0]->cat_ID;
$img = get_the_post_thumbnail_url() ? get_the_post_thumbnail_url() : wp_get_attachment_image_url(108);
?>
<!--ITEM-->
<div class="item">
	<a href="<?php the_permalink() ?>"><img
			src="<?php echo $img?>"
			alt="<?php the_title() ?>"></a>
	<div class="meta">
		<span class="author">Posted by <?php the_author() ?></span>
		<span class="count">
			<?php
			if($catID == 2) echo 'Like: ' . getLike(get_the_ID());
			if($catID == 3) echo 'View: ' . getView(get_the_ID());
			?>
		</span>
		<span class="date"><?php the_modified_date() ?></span>
	</div>
	<h3>
		<a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
	<p class="desc"><?php echo mb_substr(get_the_excerpt(), 0, 50) ?>...</p>
</div>
