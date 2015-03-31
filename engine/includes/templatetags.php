<?php
/**
 * Infinity Theme: template tags
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.0
 */

/**
 * Print a basic title
 *
 * @package Infinity
 * @subpackage base
 */

/**
 * Print the <title> tag based on what is being viewed.
 *
 * If the WordPress SEO plugin is supported, wp_title() is called with no args.
 *
 * @global int $page
 * @global int $paged
 * @uses infinity_plugin_supported()
 */
function infinity_base_title()
{
	global $page, $paged;

	// is WordPress SEO plugin supported?
	if ( true === infinity_plugin_supported( 'wordpress-seo' ) ) {

		// yes, print title with no args
		wp_title();
		
	} else {
		
		// no, print our own seo title
		wp_title( '|', true, 'right' );

		// add the blog name.
		bloginfo( 'name' );

		// try to get the blog description
		$site_description = get_bloginfo( 'description', 'display' );

		// got a site desc and on home/front page?
		if (
			false === empty( $site_description ) &&
			true === (
				true === is_home() ||
				true === is_front_page()
			)
		) {
			// yes, print it
			echo ' | ' . $site_description;
		}

		// have a page number?
		if ( $paged >= 2 || $page >= 2 ) {
			// yes, print it
			echo ' | ';
			printf( __( 'Page %s', 'infinity' ), max( $paged, $page ) );
		}
	}
}

/**
 * Returns true if introduction boxes are supported and should be displayed.
 *
 * @package Infinity
 * @subpackage base
 * @return boolean
 */
function infinity_base_show_intro_box()
{
	// are author boxes supported, and has the author filled out their description?
	return current_theme_supports( 'infinity:post', 'intro-boxes' );
}

/**
 * Returns true if author has filled out their description in profile.
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_base_show_author_box()
{
	// are author boxes supported, and has the author filled out their description?
	return (
		current_theme_supports( 'infinity:post', 'author-boxes' ) &&
		get_the_author_meta( 'description' )
	);
}

/**
 * Show author box if on an author page.
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_base_auto_author_box()
{
	// on author page?
	if ( is_author() ):
		// queue the first post, that way we know who the author is when we
		// try to get their name, URL, description, avatar, etc.
		if ( have_posts() ):
			// set up this loop
			the_post();
			// load author box
			get_template_part( 'templates/parts/author-box' );
			// reset the loop so we don't break later queries
			rewind_posts();
		endif;
	endif;
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function infinity_posted_on()
{
	printf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'infinity' ), get_the_author() ) ),
		get_the_author()
	);
}

// define the_post_name() tag if not already exists.
if ( false === function_exists( 'the_post_name' ) ) {
	/**
	* Echo the post name (slug)
	*/
	function the_post_name()
	{
		// use global post
		global $post;

		// post_name property is the slug
		echo $post->post_name;
	}
}

//
// Custom Conditionals
//

if ( false === function_exists( 'is_forum_page' ) ) {
	/**
	 * Returns true if on any forum page.
	 *
	 * @package Infinity
	 * @subpackage conditionals
	 * @return boolean
	 */
	function is_forum_page()
	{
		return (
			true === infinity_plugin_supported( 'bbpress' ) &&
			true === infinity_bbpress_is_page()
		);
	}
}

/**
 * Returns true if not in admin dir
 *
 * @package Infinity
 * @subpackage conditionals
 * @return boolean
 */
function is_not_admin()
{
    return ( !is_admin() );
}
