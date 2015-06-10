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
 * Returns true if author boxes are supported and author has filled out their description in profile.
 *
 * @package Infinity
 * @subpackage base
 * @return boolean
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
		esc_attr( sprintf( __( 'View all posts by %s', 'infinity-engine' ), get_the_author() ) ),
		get_the_author()
	);
}

if ( !function_exists( 'infinity_comment_nav' ) ) {
	/**
	 * Display navigation to next/previous comments when applicable.
	 *
	 * @since Twenty Fifteen 1.0
	 */
	function infinity_comment_nav() {
		// Are there comments to navigate through?
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
			?>
			<nav class="navigation comment-navigation" role="navigation">
				<div class="nav-links">
					<?php

						// previous link
						$prev_link = get_previous_comments_link( __( 'Older Comments', 'infinity-engine' ) );

						// have prev link?
						if ( $prev_link ):
							?>
							<div class="nav-previous">
								<?php echo $prev_link; ?>
							</div>
							<?php
						endif;

						// next link
						$next_link = get_next_comments_link( __( 'Newer Comments', 'infinity-engine' ) );

						// have next link?
						if ( $next_link ):
							?>
							<div class="nav-next">
								<?php echo $next_link; ?>
							</div>
							<?php
						endif;

					?>
				</div><!-- .nav-links -->
			</nav><!-- .comment-navigation -->
			<?php
		endif;
	}
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
			true === infinity_bbp_is_page()
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
