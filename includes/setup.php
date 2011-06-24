<?php
/**
 * Infinity Theme: WordPress setup
 *
 * @author Bowe Frankema <bowromir@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @subpackage includes
 * @since 1.0
 */

// add post formats
// TODO put this in infinty.ini when possible
add_theme_support( 'post-formats', array( 'aside', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video', 'audio' ) );

/**
 * Setup post thumbnail sizes
 */
function infinity_base_post_thumb_sizes()
{
	if ( current_theme_supports( 'post-thumbnails' ) ) {
		set_post_thumbnail_size( 35, 35, true );
		add_image_size( 'post-image', 674, 140, true ); // Permalink thumbnail size
		add_image_size( 'slider-image', 674, 140, true ); // Permalink thumbnail size
		add_image_size( 'featured-single', 960, 160, true ); // Permalink thumbnail size
		add_image_size( 'slider-full', 960, 300, true ); // Permalink thumbnail size
		add_image_size( 'large', 680, '', true ); // Large thumbnails
		add_image_size( 'medium', 250, '', true ); // Medium thumbnails
		add_image_size( 'small', 125, '', true ); // Small thumbnails
		add_image_size( 'thumbnail-large', 600, 200, true ); // Alt Large thumbnails
		add_image_size( 'thumbnail-post', 210, 160, true ); // Post thumbnails
		add_image_size( 'thumbnail-archive', 680, 180, true ); // Archive thumbnails
		add_image_size( 'thumbnail-portfolio', 700, '', true ); // Portfolio thumbnails
	}
}
add_action( 'init', 'infinity_base_post_thumb_sizes' );

/**
 * Register menus
 */
function infinity_base_register_menus()
{
	register_nav_menu( 'over-menu', __( 'Top Menu' ) );
	register_nav_menu( 'primary-menu', __( 'Primary Menu' ) );
	register_nav_menu( 'sub-menu', __( 'Sub Menu' ) );
	register_nav_menu( 'footer-menu', __( 'Footer Menu' ) );
}
add_action( 'init', 'infinity_base_register_menus' );

/**
 * Register sidebars
 */
function infinity_base_register_sidebars()
{
	register_sidebar(array(
		'name' => 'Blog Sidebar',
		'id' => 'blog-sidebar',
		'description' => "The blog widget area",
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>'
	));
 
	register_sidebar(array(
		'name' => 'Page Sidebar',
		'id' => 'page-sidebar',
		'description' => "The page widget area",
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>'
	));

	register_sidebar(array(
		'name' => 'Footer Left',
		'id' => 'footer-left',
		'description' => "The left footer widget",
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>'
	));

	register_sidebar(array(
		'name' => 'Footer Middle',
		'id' => 'footer-middle',
		'description' => "The middle footer widget",
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>'
	));

	register_sidebar(array(
		'name' => 'Footer Right',
		'id' => 'footer-right',
		'description' => "The right footer widget",
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>'
	));
}
add_action( 'init', 'infinity_base_register_sidebars' );

/**
 * Setup custom post type for the slider
 */
function infinity_base_slider_setup()
{
	$labels = array(
		'name' => _x('slides', 'post type general name', 'bpminimal'),
		'singular_name' => _x('slides', 'post type singular name', 'bpminimal'),
		'add_new' => _x('Add Slide', 'infobox', 'bpminimal'),
		'add_new_item' => __('Add New Slide', 'bpminimal'),
		'edit_item' => __('Edit Slide', 'bpminimal'),
		'new_item' => __('New Slide', 'bpminimal'),
		'view_item' => __('View Slide', 'bpminimal'),
		'search_items' => __('Search Slide', 'bpminimal'),
		'not_found' =>  __('No Slides fount', 'bpminimal'),
		'not_found_in_trash' => __('No Slides are found in Trash', 'bpminimal'),
		'parent_item_colon' => ''
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_icon' => get_stylesheet_directory_uri() .'/_inc/images/slide.png',
		'menu_position' => null,
		'supports' => array('title','editor', 'thumbnail' )
	);

	register_post_type( 'slides', $args );
}
add_action( 'init', 'infinity_base_slider_setup' );

/**
 * Custom navigation menus walker
 * 
 * Kudos to Kriesi (Kriesi.at) and Orman Clark (PremiumPixels.com)
 */
class menu_walker extends Walker_Nav_Menu
{
	function start_el(&$output, $item, $depth, $args)
	{
		global $wp_query;

		$indent = ( $depth ) ? str_repeat( "", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="'. esc_attr( $class_names ) . '"';

		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$prepend = '';
		$append = '';
		$description  = ! empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';

		if( $depth != 0) {
			$description = $append = $prepend = "";
		}

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'><span>';
		$item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
		$item_output .= '</span></a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/**
 * Add proper facebook meta data to posts
 * 
 * @todo this should be a feature
 */
function infinity_base_facebook_meta_tags()
{
	global $post;

	if ( is_singular() ): ?>
		<meta property="og:title" content="<?php the_title() ?>" />
	<?php
		if ( current_theme_supports( 'post-thumbnails' ) && has_post_thumbnail( $post->ID ) ):
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail', false );
	?>
			<meta property="og:image" content="<?php print $thumbnail[0] ?>" />
	<?php
		endif;
		
		if ( get_the_excerpt() != '' ):
	?>
			<meta property="og:description" content="<?php print strip_tags( get_the_excerpt() ) ?>" />
	<?php
		endif;
	endif;
}
add_action( 'wp_head', 'infinity_base_facebook_meta_tags' );

?>
