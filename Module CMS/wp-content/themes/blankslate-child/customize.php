<?php
/**
 * Created by PhpStorm.
 * User: NhatLe
 * Date: 22-Aug-18
 * Time: 16:03
 */

//error_reporting(0);

define( 'TFG_THEME_DIR', get_theme_root() . '/blankslate-child' );
define( 'TFG_THEME_URL', get_theme_root_uri() . '/blankslate-child' );
define( 'TFG_THEME_CSS_URL', TFG_THEME_URL . '/css' );
define( 'TFG_THEME_JS_URL', TFG_THEME_URL . '/js' );

/**
 * Add css and js
 */
if ( ! is_admin() ) {
	wp_enqueue_style( 'tfg-style', TFG_THEME_CSS_URL . '/style.css', [], '1.0' );
}
wp_enqueue_script( 'tfg-script', TFG_THEME_JS_URL . '/main.js', [ 'jquery' ], '1.0', true );
wp_localize_script( 'tfg-script', 'ajax_var', [
	'url' => admin_url( 'admin-ajax.php' )
] );


/**
 * add menu
 */

add_action( 'admin_menu', function () {
	add_menu_page( 'Food Recipe', 'Food Recipe', 'manage_options', 'food-recipe', function () {
		wp_redirect( admin_url( 'edit.php?cat=2' ) );
	}, 'dashicons-admin-post', 5 );
	add_menu_page( 'Food Blog', 'Food Blog', 'read', 'food-blog', function () {
		wp_redirect( admin_url( 'edit.php?cat=3' ) );
	}, 'dashicons-admin-post', 6 );
} );

/**
 * Init post meta
 */

add_filter( 'wp_insert_post_data', function ( $data, $postArr ) {
	$catID  = $postArr['post_category'][1];
	$postID = $postArr['ID'];
	if ( $catID == 2 ) {
		add_post_meta( $postID, 'tfg-like', 0 );
	}
	if ( $catID == 3 ) {
		add_post_meta( $postID, 'tfg-view', 0 );
	}

	return $data;
}, 99, 2 );

/**
 * Count view
 */
function getView( $postID ) {
	$key   = 'tfg-view';
	$count = get_post_meta( $postID, $key, true );

	return empty( $count ) ? 0 : $count;
}

function setView( $postID ) {
	$key   = 'tfg-view';
	$count = get_post_meta( $postID, $key, true );
	if ( $count == '' ) {
		$count = 1;
		delete_post_meta( $postID, $key );
		add_post_meta( $postID, $key, $count );
	} else {
		$count ++;
		update_post_meta( $postID, $key, $count );
	}
}

/**
 * Count Like
 */
function getLike( $postID ) {
	$key   = 'tfg-like';
	$count = get_post_meta( $postID, $key, true );

	return empty( $count ) ? 0 : $count;
}

function setLike() {
	$key    = 'tfg-like';
	$postID = $_POST['postID'];
	$count  = get_post_meta( $postID, $key, true );

	$listID = unserialize( get_option( $key, [] ) );
	$userID = get_current_user_id();

	if ( ! in_array( $userID, $listID ) ) {
		$listID[] = $userID;
		update_option( $key, serialize( $listID ) );
		if ( $count == '' ) {
			$count = 1;
			delete_post_meta( $postID, $key );
			add_post_meta( $postID, $key, $count );
		} else {
			$count ++;
			update_post_meta( $postID, $key, $count );
		}

	}

	echo getLike( $postID );
}

/**
 * Add Column
 */

add_filter( 'manage_posts_columns', function ( $col ) {
	if ( $_GET['cat'] == 2 ) {
		$col['like'] = 'Like';
	}
	if ( $_GET['cat'] == 3 ) {
		$col['view'] = 'View';
	}

	return $col;
} );

add_action( 'manage_posts_custom_column', function ( $col, $postID ) {
	if ( $col == 'like' ) {
		echo getLike( $postID );
	}
	if ( $col == 'view' ) {
		echo getView( $postID );
	}
}, 10, 2 );

add_action( 'widgets_init', 'blankslate_widgets_init' );
function blankslate_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar Widget Area', 'blankslate' ),
		'id'            => 'primary-widget-area',
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget'  => "</div>",
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}

add_action( 'widgets_init', 'blankslate_widgets_init_header' );
function blankslate_widgets_init_header() {
	register_sidebar( array(
		'name'          => __( 'Header Widget Area', 'blankslate' ),
		'id'            => 'header-widget-area',
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget'  => "</div>",
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}

add_action( 'widgets_init', 'blankslate_widgets_init_footer' );
function blankslate_widgets_init_footer() {
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area', 'blankslate' ),
		'id'            => 'footer-widget-area',
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget'  => "</div>",
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}

add_action( 'widgets_init', function () {
	unregister_widget( 'WP_Widget_Search' );
} );

require_once TFG_THEME_DIR . '/widget/BlogPost.php';
register_widget( 'BlogPost' );

/**
 * Add shortcode
 */

add_action( 'init', function () {
	add_shortcode( 'food-recipe', function () {
		$args    = [
			'cat'            => 2,
			'meta_key'       => 'tfg-like',
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC',
			'posts_per_page' => 4,
			'post_status'    => 'publish',
		];
		$wpQuery = new WP_Query( $args );
		require_once TFG_THEME_DIR . '/shortcode/food-recipe.php';
		wp_reset_postdata();
	} );

	add_shortcode( 'food-blog', function () {
		$args    = [
			'cat'            => 3,
			'meta_key'       => 'tfg-view',
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC',
			'posts_per_page' => 4,
			'post_status'    => 'publish',
		];
		$wpQuery = new WP_Query( $args );
		require_once TFG_THEME_DIR . '/shortcode/food-blog.php';
		wp_reset_postdata();
	} );
} );

/**
 * Ajax
 */

add_action( 'wp_ajax_tfg_like', 'setLike' );
add_action( 'wp_ajax_nopriv_tfg_like', 'setLike' );

/**
 * Add role
 */
add_role( 'user', 'User', get_role( 'contributor' )->capabilities );
$roleUser = get_role( 'user' );
$roleUser->add_cap( 'upload_files' );

add_role( 'moderator', 'Moderator', get_role( 'author' )->capabilities );
$roleUser = get_role( 'moderator' );
$roleUser->add_cap( 'edit_others_posts' );
$roleUser->add_cap( 'delete_others_posts' );


function checkRole( $role ) {
	$user = wp_get_current_user();

	return in_array( $role, (array) $user->roles );
}

function restrictCategory( $cats ) {
	$onPage = ( strpos( $_SERVER['PHP_SELF'], 'edit.php' ) || strpos( $_SERVER['PHP_SELF'], 'post-new.php' ) || strpos( $_SERVER['PHP_SELF'], 'post.php' ) );
	if ( is_admin() && $onPage && checkRole( 'user' ) ) {
		$count = count( $cats );
		for ( $i = 0; $i < $count; $i ++ ) {
			if ( $cats[ $i ]->term_id != 3 ) {
				unset( $cats[ $i ] );
			}
		}
	}

	if ( is_admin() && $onPage && checkRole( 'moderator' ) ) {
		$count = count( $cats );
		for ( $i = 0; $i < $count; $i ++ ) {
			if ( $cats[ $i ]->term_id != 3 ) {
				unset( $cats[ $i ] );
			}
		}
	}

	return $cats;
}

add_filter( 'get_terms', 'restrictCategory' );

add_action( 'admin_init', function () {

	$role = wp_get_current_user()->roles[0];
	if ( $role == 'user' && strpos( $_SERVER['PHP_SELF'], 'edit.php' ) && ! strpos( $_SERVER['REQUEST_URI'], 'cat=3' ) ) {

		wp_redirect( admin_url( 'edit.php?s&post_status=all&post_type=post&action=-1&m=0&cat=3&filter_action=Filter' ) );
		exit();
	}
	if ( $role == 'moderator' && strpos( $_SERVER['PHP_SELF'], 'edit.php' ) && ! strpos( $_SERVER['REQUEST_URI'], 'cat=3' ) ) {
		wp_redirect( admin_url( 'edit.php?s&post_status=all&post_type=post&action=-1&m=0&cat=3&filter_action=Filter' ) );
		exit();
	}
} );