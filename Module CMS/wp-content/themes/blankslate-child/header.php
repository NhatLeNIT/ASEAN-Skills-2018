<?php
/**
 * Created by PhpStorm.
 * User: NhatLe
 * Date: 22-Aug-18
 * Time: 16:01
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thailand Food Recipe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head() ?>
</head>
<body>

<div id="wrapper">
    <div id="header">
		<?php dynamic_sidebar( 'header-widget-area' ) ?>
    </div>
    <div id="nav" <?php if ( is_user_logged_in() )
		echo 'class="login"' ?>>
        <div class="container">
            <div class="row">
                <div id="nav-container" class="col-12">
                    <a href="<?php echo home_url('/') ?>"><img src="http://competitorvn.asc.local/08_cms_module/wp-content/themes/blankslate-child/images/logo/logo.png"
                         alt="logo" id="logo"></a>
					<?php
					wp_nav_menu( [
						'container_id'   => 'menu',
						'theme_location' => 'main-menu'
					] )
					?>
                    <div id="search">
                        <form action="<?php echo home_url('/') ?>">
                            <input type="search" name="s" id="search-box" placeholder="Search recipe" value="<?php the_search_query() ?>">
                            <button type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <img src="http://competitorvn.asc.local/08_cms_module/wp-content/themes/blankslate-child/images/bar.png"
             alt="bar" id="bar">
    </div>

    <div id="banner">
        <img src="http://competitorvn.asc.local/08_cms_module/wp-content/themes/blankslate-child/images/food/others/Green%20Papaya%20Salad.jpg"
             alt="banner">
    </div>

    <main id="main">
        <div class="container">
            <div class="row">
                <div id="content" class="col-9">
