<?php
/**
 * Created by PhpStorm.
 * User: NhatLe
 * Date: 22-Aug-18
 * Time: 22:15
 */
?>
<div class="widget-container">
	<h2 class="widget-title">Food Blog</h2>
	<div class="grid-container">
		<?php
		if ( $wpQuery->have_posts() ) : while ( $wpQuery->have_posts() ) : $wpQuery->the_post();
			get_template_part( 'entry' );
		endwhile; endif;
		?>
	</div>
</div>
