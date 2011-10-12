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
 *
 * @package Infinity
 * @subpackage base
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
	}
}
add_action( 'init', 'infinity_base_post_thumb_sizes' );

/**
 * Register menus
 *
 * @package Infinity
 * @subpackage base
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
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @package Infinity
 * @subpackage base
 * @see register_sidebar()
 * @param string $id Sidebar ID, 'id' arg passed to register_sidebar()
 * @param string $name Sidebar name, 'name' arg passed to register_sidebar()
 * @param string $desc Sedebar description, 'description' arg passed to register_sidebar()
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
 *
 * @package Infinity
 * @subpackage base
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
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @package Infinity
 * @subpackage base
 * @see wp_nav_menu()
 * @param string $theme_location Theme location, 'theme_location' arg passed to wp_nav_menu()\
 */
function infinity_base_nav_menu( $theme_location )
{
	wp_nav_menu( array(
		'theme_location' => $theme_location,
		'menu_class' => 'sf-menu',
		'container' => '',
		'fallback_cb' => 'infinity_base_page_menu',
		'walker' => new Infinity_Base_Walker_Nav_Menu()
	));
}

/**
 * Display a page menu using a custom page walker
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @package Infinity
 * @subpackage base
 * @see wp_list_pages()
 */
function infinity_base_page_menu()
{
	// open the list ?>
	<ul class="sf-menu"><?php
		wp_list_pages(array(
			'title_li' => null,
			'walker' => new Infinity_Base_Walker_Page_Menu()
		));
	// close list?>
	</ul><?php
}

/**
 * Render a single superfish list item
 *
 * @param array $args
 * @param boolean $output
 * @return void|string
 */
function infinity_base_superfish_list_item( $args, $output = true )
{
	// default values
	$defaults = array(
		'id' => null,
		'title' => null,
		'close_item' => true,
		'li_id' => null,
		'li_classes' => array(),
		'a_id' => null,
		'a_classes' => array(),
		'a_target' => null,
		'a_rel' => null,
		'a_href' => null,
		'a_before' => null,
		'a_after' => null,
		'a_open' => null,
		'a_close' => null
	);

	// parse and extract
	$r = wp_parse_args( $args, $defaults );
	extract( $r );

	// handle empty list id
	if ( empty( $li_id ) ) {
		$li_id = 'menu-item-' . $id;
	}
	
	// list attributes
	$attr_li[] = sprintf( 'id="%s"', esc_attr( $li_id ) );

	if ( count( $li_classes ) ) {
		$attr_li[] = sprintf( 'class="%s"', esc_attr( implode( ' ', $li_classes ) ) );
	}

	// anchor attributes
	$attr_anchor = array();

	if ( $a_id ) {
		$attr_anchor[] = sprintf( 'id="%s"', esc_attr( $a_id ) );
	}
	if ( count( $a_classes ) ) {
		$attr_anchor[] = sprintf( 'class="%s"', esc_attr( implode( ' ', $a_classes ) ) );
	}
	if ( $a_href ) {
		$attr_anchor[] = sprintf( 'href="%s"', esc_attr( $a_href ) );
	}
	if ( $a_title ) {
		$attr_anchor[] = sprintf( 'title="%s"', esc_attr( $a_title ) );
	}
	if ( $a_target ) {
		$attr_anchor[] = sprintf( 'target="%s"', esc_attr( $a_target ) );
	}
	if ( $a_rel ) {
		$attr_anchor[] = sprintf( 'rel="%s"', esc_attr( $a_rel ) );
	}

	// turn on output buffering if we are returning a string
	if ( !$output ) {
		ob_start();
	}

	// render the list item ?>
	<li <?php print implode( ' ', $attr_li ) ?>>
		<?php print $a_before ?>
		<a <?php print implode( ' ', $attr_anchor ) ?>>
			<?php print $a_open ?>
			<span><?php print esc_html( $title ) ?></span>
			<?php print $a_close ?>
		</a>
		<?php print $a_after ?>
	<?php if ( $close_item ): ?>
	</li>
	<?php endif; ?>

	<?php
	if ( !$output ) {
		return ob_get_clean();
	}
}

/**
 * Add Pagination
 *
 * @package Infinity
 * @subpackage base
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
 * @package Infinity
 * @subpackage base
 * @todo move this to a feature extension, no direct plugin support
 */
function infinity_base_yoast_breadcrumbs() {
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
	}
}
add_action( 'open_content', 'infinity_base_yoast_breadcrumbs' );

?>