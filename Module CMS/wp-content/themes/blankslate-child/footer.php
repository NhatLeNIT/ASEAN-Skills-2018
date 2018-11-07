<?php
/**
 * Created by PhpStorm.
 * User: NhatLe
 * Date: 22-Aug-18
 * Time: 16:02
 */
?>
</div>
<?php get_sidebar() ?>
</div>
</div>
</main>

<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="widget-container">
                    <h2 class="widget-title">Popular Recipe</h2>
                    <div class="grid-container">
						<?php
						$args    = [
							'cat'            => 2,
							'meta_key'       => 'tfg-like',
							'orderby'        => 'meta_value_num',
							'order'          => 'DESC',
							'posts_per_page' => 5,
							'post_status'    => 'publish',
						];
						$wpQuery = new WP_Query( $args );
						if ( $wpQuery->have_posts() ) : while ( $wpQuery->have_posts() ) : $wpQuery->the_post();
							get_template_part( 'entry' );
						endwhile; endif;
						wp_reset_postdata();
						?>

                    </div>
                </div>

                <div id="footer-bottom">
                    <h3>Copyright Vietnam 2018</h3>
					<?php dynamic_sidebar( 'footer-widget-area' ) ?>
                </div>
            </div>
        </div>
    </div>

</footer>
</div>

<?php wp_footer() ?>
</body>
</html>
