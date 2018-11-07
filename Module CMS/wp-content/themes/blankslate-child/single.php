<?php
/**
 * Created by PhpStorm.
 * User: NhatLe
 * Date: 22-Aug-18
 * Time: 22:33
 */
?>
<?php get_header() ?>

    <div class="widget-container" id="single">
		<?php if ( have_posts() ): while ( have_posts() ): the_post();
			$catID = get_the_category()[0]->cat_ID;
			?>
            <h1 class="widget-title"><?php the_title() ?></h1>
            <div class="grid-container-sidebar">
                <!--ITEM-->
                <div class="item">
                    <div class="meta">
                        <span class="author">Posted by <?php the_author() ?></span>
                        <span class="count">
			<?php
			if ( $catID == 2 ) {
				echo 'Like: ' . getLike( get_the_ID() );
			}
			if ( $catID == 3 ) {
				echo 'View: ' . getView( get_the_ID() );
			}
			?>
		</span>
                        <span class="date"><?php the_modified_date() ?></span>
                    </div>
					<?php if ( $catID == 2  && is_user_logged_in()): ?>
                        <div class="post-action">
                            <button class="btn-like" data-id="<?php the_ID() ?>">Like post</button>
                        </div>
					<?php endif; ?>
                    <div class="content">
						<?php the_content() ?>

						<?php if ( $catID == 2 ): ?>
                            <div>
                                <h2>Link Ingredients</h2>
                                <ul>
									<?php
									$fields = get_field( 'ingredients' );
									foreach ( $fields as $field ):
										?>
                                        <li>
                                            <a href="<?php echo get_the_permalink( $field->ID ) ?>"><?php echo get_the_title( $field->ID ) ?></a>
                                        </li>
									<?php endforeach; ?>
                                </ul>
                            </div>
						<?php endif; ?>
	                    <?php if ( $catID == 4 ): ?>
                            <div>
                                <h2>Ingredient Information</h2>
                                <ul>
				                    <?php
				                    $calorie = get_field( 'calorie' );
				                    $fat = get_field( 'fat' );
				                    $carbohydrate= get_field( 'carbohydrate' );
					                    ?>
                                        <li>Calorie: <?php echo $calorie ?></li>
                                        <li>Fat: <?php echo $fat ?></li>
                                        <li>Carbohydrate: <?php echo $carbohydrate?></li>
                                </ul>
                            </div>
	                    <?php endif; ?>
                    </div>
                </div>
            </div>
		<?php endwhile; endif; ?>
        <?php comments_template() ?>
    </div>
<?php get_footer() ?>