<?php
/**
 * Created by PhpStorm.
 * User: NhatLe
 * Date: 22-Aug-18
 * Time: 22:28
 */
?>
<?php get_header() ?>
<div class="widget-container" id="category">
    <h1 class="widget-title"><?php single_cat_title() ?></h1>
    <div class="grid-container-sidebar">
		<?php
		if ( have_posts() ): while ( have_posts() ): the_post();
			get_template_part( 'entry' );
		endwhile; endif;
		?>
    </div>
    <?php the_posts_pagination() ?>
</div>
<?php get_footer() ?>
