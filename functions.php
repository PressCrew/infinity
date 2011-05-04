<?php
/**
 * Infinity Theme: theme functions
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package functions
 * @since 1.0
 */

// DO NOT EDIT these constants for any reason
define( 'INFINITY_VERSION', '1.0b1' );
define( 'INFINITY_NAME', 'infinity' );
define( 'INFINITY_THEME_DIR', get_theme_root( INFINITY_NAME ) . DIRECTORY_SEPARATOR . INFINITY_NAME );
define( 'INFINITY_THEME_URL', get_theme_root_uri( INFINITY_NAME ) . '/' . INFINITY_NAME );
define( 'INFINITY_API_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'api' );
define( 'INFINITY_API_URL', INFINITY_THEME_URL . '/api' );
define( 'INFINITY_PIE_DIR', INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'pie' );
define( 'INFINITY_PIE_URL', INFINITY_API_URL . '/pie' );
define( 'INFINITY_ADMIN_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'dashboard' );
define( 'INFINITY_ADMIN_URL', INFINITY_THEME_URL . '/dashboard' );
define( 'INFINITY_EXPORT_DIR', INFINITY_THEME_DIR . DIRECTORY_SEPARATOR . 'export' );
define( 'INFINITY_EXPORT_URL', INFINITY_THEME_URL . '/export' );
define( 'INFINITY_EXTRAS_DIR', get_theme_root( INFINITY_NAME ) . DIRECTORY_SEPARATOR . INFINITY_NAME . '-extras' );
define( 'INFINITY_EXTRAS_URL', get_theme_root_uri( INFINITY_NAME ) . '/' . INFINITY_NAME . '-extras' );
define( 'INFINITY_TEXT_DOMAIN', INFINITY_NAME );
define( 'infinity_text_domain', INFINITY_TEXT_DOMAIN ); // for code completion
define( 'INFINITY_ADMIN_PAGE', INFINITY_NAME . '-theme' );
define( 'INFINITY_ADMIN_TPLS_DIR', INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'templates' );
define( 'INFINITY_ADMIN_DOCS_DIR', INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'docs' );

// load PIE and initialize
require_once( INFINITY_PIE_DIR . DIRECTORY_SEPARATOR . 'loader.php' );
Pie_Easy_Loader::init( INFINITY_PIE_URL );

// load Infinity API
require_once( INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'scheme.php' );
require_once( INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'options.php' );
require_once( INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'features.php' );
require_once( INFINITY_API_DIR . DIRECTORY_SEPARATOR . 'l10n.php' );

// initialize scheme
infinity_scheme_init();
infinity_options_init();

if ( is_admin() ) {
	// only load admin functionality if the dashboard is actually loaded
	require_once( INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'loader.php' );
} else {
	// some features need initialization
	add_action( 'after_setup_theme', array('Infinity_Features', 'init') );
}

//
// Actions & Filters
//

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override infinity_setup() in a child theme, add your own infinity_setup to your child theme's
 * functions.php file.
 *
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 */
function infinity_setup()
{
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( INFINITY_TEXT_DOMAIN, TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) ) {
		require_once( $locale_file );
	}

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', infinity_text_domain ),
	) );
}
add_action( 'after_setup_theme', 'infinity_setup' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 */
function infinity_page_menu_args( $args )
{
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'infinity_page_menu_args' );

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @return int
 */
function infinity_excerpt_length( $length )
{
	return 40;
}
add_filter( 'excerpt_length', 'infinity_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @return string "Continue Reading" link
 */
function infinity_continue_reading_link()
{
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', infinity_text_domain ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and infinity_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @return string An ellipsis
 */
function infinity_auto_excerpt_more( $more )
{
	return ' &hellip;' . infinity_continue_reading_link();
}
add_filter( 'excerpt_more', 'infinity_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function infinity_custom_excerpt_more( $output )
{
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= infinity_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'infinity_custom_excerpt_more' );

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Infinity's style.css
 *
 * @return string The gallery style filter, with the styles themselves removed.
 */
function infinity_remove_gallery_css( $css )
{
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'infinity_remove_gallery_css' );

/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own infinity_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function infinity_comment( $comment, $args, $depth )
{
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s <span class="says">says:</span>', infinity_text_domain ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', infinity_text_domain ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', infinity_text_domain ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', infinity_text_domain ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', infinity_text_domain ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', infinity_text_domain), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override infinity_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @uses register_sidebar
 */
function infinity_widgets_init()
{
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', infinity_text_domain ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', infinity_text_domain ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Secondary Widget Area', infinity_text_domain ),
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary widget area', infinity_text_domain ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', infinity_text_domain ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', infinity_text_domain ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', infinity_text_domain ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', infinity_text_domain ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', infinity_text_domain ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', infinity_text_domain ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 6, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', infinity_text_domain ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area', infinity_text_domain ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
/** Register sidebars by running infinity_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'infinity_widgets_init' );

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 */
function infinity_remove_recent_comments_style()
{
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'infinity_remove_recent_comments_style' );

/**
 * Prints HTML with meta information for the current postâ€”date/time and author.
 */
function infinity_posted_on()
{
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', infinity_text_domain ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', infinity_text_domain ), get_the_author() ),
			get_the_author()
		)
	);
}

/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 */
function infinity_posted_in()
{
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', infinity_text_domain );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', infinity_text_domain );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', infinity_text_domain );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
?>
