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
    <h1 class="widget-title">Search result for: <?php the_search_query() ?></h1>
    <div class="grid-container-sidebar">
		<?php
        $wp_query->query['cat'] = 2;
        query_posts($wp_query->query);
		if ( have_posts() ): while ( have_posts() ): the_post();
			get_template_part( 'entry' );
		endwhile; endif;
		?>
    </div>
    <?php the_posts_pagination() ?>
</div>
<?php get_footer() ?>
