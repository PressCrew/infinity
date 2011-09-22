<?php
/**
 * Infinity Theme: WordPress setup
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.0
 */

/**
 * Include custom comments, template tags, and walker classes
 */
require_once( INFINITY_BASE_DIR . DIRECTORY_SEPARATOR . 'comments.php' );
require_once( INFINITY_BASE_DIR . DIRECTORY_SEPARATOR . 'templatetags.php' );
require_once( INFINITY_BASE_DIR . DIRECTORY_SEPARATOR . 'walkers.php' );
 
// add post formats
add_theme_support(
	'post-formats',
	array(
		'aside',
		'audio',
		'chat',
		'gallery',
		'image',
		'link',
		'quote',
		'status',
		'video'
	)
);


// Set the content width based on the theme's design and stylesheet.
// Used to set the width of images and content. Should be equal to the
// width the theme is designed for, generally via the style.css stylesheet.
if ( ! isset( $content_width ) ) {
	$content_width = 760;	
	add_theme_support( 'automatic-feed-links' );
}

/**
 * Setup post thumbnail sizes
 */
function infinity_base_post_thumb_sizes()
{
	if ( current_theme_supports( 'post-thumbnails' ) ) {
		set_post_thumbnail_size( 35, 35, true );
		add_image_size( 'post-image', 674, 140, true );
		add_image_size( 'featured-single', 960, 160, true ); 
		add_image_size( 'slider-full', 960, 300, true );
		add_image_size( 'large', 680, '', true );
		add_image_size( 'medium', 250, '', true );
		add_image_size( 'small', 125, '', true );
		add_image_size( 'thumbnail-large', 600, 200, true );
		add_image_size( 'thumbnail-post', 210, 160, true );
		add_image_size( 'thumbnail-archive', 680, 180, true );
		add_image_size( 'thumbnail-portfolio', 700, '', true );
	}
}
add_action( 'init', 'infinity_base_post_thumb_sizes' );

/**
 * Register menus
 */
function infinity_base_register_menus()
{
	register_nav_menu( 'over-menu', __( 'Top Menu', infinity_text_domain ) );
	register_nav_menu( 'main-menu', __( 'Main Menu', infinity_text_domain ) );
	register_nav_menu( 'sub-menu', __( 'Sub Menu' ), infinity_text_domain );
	register_nav_menu( 'footer-menu', __( 'Footer Menu', infinity_text_domain ) );
}
add_action( 'init', 'infinity_base_register_menus' );

/**
 * Register one sidebar
 */
function infinity_base_register_sidebar( $id, $name, $desc )
{
	register_sidebar( array(
		'id' => $id,
		'name' => $name,
		'description' => $desc,
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>'
	));
}

/**
 * Register all sidebars
 */
function infinity_base_register_sidebars()
{
	// blog
	infinity_base_register_sidebar(
		'blog-sidebar',
		'Blog Sidebar',
		'The blog widget area'
	);
	// page
	infinity_base_register_sidebar(
		'page-sidebar',
		'Page Sidebar',
		'The page widget area'
	);
	// footer left
	infinity_base_register_sidebar(
		'footer-left',
		'Footer Left',
		'The left footer widget'
	);
	// footer middle
	infinity_base_register_sidebar(
		'footer-middle',
		'Footer Middle',
		'The middle footer widget'
	);
	// footer right
	infinity_base_register_sidebar(
		'footer-right',
		'Footer Right',
		'The right footer widget'
	);
}
add_action( 'init', 'infinity_base_register_sidebars' );

/**
 * Display a nav menu using a custom walker
 */
function infinity_base_nav_menu( $theme_location )
{
	wp_nav_menu( array(
		'theme_location' => $theme_location,
		'menu_class' => 'sf-menu',
		'container' => '',
		'walker' => new Infinity_Base_Walker_Nav_Menu()
	));
}

/**
 * Add Pagination
 *
 * @todo write a paginator from scratch, this is mental
 */
function infinity_base_paginate() {
	global $wp_query, $wp_rewrite;

	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

	$pagination = array(
		'base' => @add_query_arg('page','%#%'),
		'format' => '',
		'total' => $wp_query->max_num_pages,
		'current' => $current,
		'show_all' => true,
		'type' => 'list'
	);

	if ( $wp_rewrite->using_permalinks() ) {
		$pagination['base'] =
			user_trailingslashit(
				trailingslashit(
					remove_query_arg( 's', get_pagenum_link( 1 ) )
				) . 'page/%#%/', 'paged'
			);
	}

	if ( !empty( $wp_query->query_vars['s'] ) ) {
		$pagination['add_args'] = array(
			's' => get_query_var( 's' )
		);
	}
	
	print paginate_links( $pagination );
}

/**
 * Add Breadcrumb functionality for WordPress SEO
 * 
 * @todo move this to a feature extension, no direct plugin support
 */
function infinity_base_yoast_breadcrumbs() {
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
	}
}
add_action( 'open_content', 'infinity_base_yoast_breadcrumbs' );

?>