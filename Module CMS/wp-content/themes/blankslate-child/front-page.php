<?php
/**
 * Created by PhpStorm.
 * User: NhatLe
 * Date: 22-Aug-18
 * Time: 22:17
 */
?>
<?php
get_header();
if ( have_posts() ): while ( have_posts() ): the_post();
	the_content();
endwhile; endif;
get_footer();
?>
