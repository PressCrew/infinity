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

function infinity_base_title()
{
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;
 
	wp_title( '|', true, 'right' );
 
	// Add the blog name.
	bloginfo( 'name' );
 
	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";
 
	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', infinity_text_domain ), max( $paged, $page ) );
}

/**
 * Show author box (if on an author page)
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_base_author_box()
{
	if ( is_author() ):
		// queue the first post, that way we know who the author is when we
		// try to get their name, URL, description, avatar, etc.
		if ( have_posts() ):
			the_post();

			// if a user has filled out their description, show a bio on their entries.
			if ( get_the_author_meta( 'description' ) ):
				infinity_get_template_part( 'templates/parts/author-box' );
			endif;

			// reset the loop so we don't break later queries
			rewind_posts();
		endif;
	endif;
}

/**
 * Show sidebars based on page type (including BP components)
 *
 * @package Infinity
 * @subpackage base
 */
if ( current_theme_supports( 'infinity-sidebar-setup' ) ) {
	function infinity_base_sidebars()
	{
			if ( is_page() ) {
				global $post;
				if ( function_exists('bp_is_page') && bp_is_user() ) {
					if ( is_active_sidebar( 'member-sidebar' ) ) {
						dynamic_sidebar( 'member-sidebar' );
					} else { ?>
					<?php if( current_user_can('edit_theme_options') ) : ?>
					<div class="widget"><h4>BP Member Sidebar.</h4>
					<a href="<?php echo home_url( '/'  ); ?>wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php endif; ?><?php
					}
			
				} elseif ( function_exists('bp_is_page') && bp_is_page(BP_MEMBERS_SLUG) ) {
	                if ( is_active_sidebar( 'member-sidebar' ) ) {
	                    dynamic_sidebar( 'member-sidebar');
					} else { ?>
					<?php if( current_user_can('edit_theme_options') ) : ?>
					<div class="widget"><h4>BP Members Sidebar.</h4>
					<a href="<?php echo home_url( '/'  ); ?>wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php endif; ?><?php
					}
	           } elseif ( function_exists('bp_is_page') && bp_is_page(BP_GROUPS_SLUG) ) {
	                if ( is_active_sidebar( 'groups-sidebar' ) ) {
	                    dynamic_sidebar( 'groups-sidebar');
					} else { ?>
					<?php if( current_user_can('edit_theme_options') ) : ?>
					<div class="widget"><h4>BP Group Sidebar.</h4>
					<a href="<?php echo home_url( '/'  ); ?>wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php endif; ?><?php
					}
	            } elseif ( function_exists('bp_is_page') && bp_is_page(BP_FORUMS_SLUG) ) {
	                if ( is_active_sidebar( 'forums-sidebar' ) ) {
	                    dynamic_sidebar( 'forums-sidebar');
					} else { ?>
					<?php if( current_user_can('edit_theme_options') ) : ?>
					<div class="widget"><h4>BP Forums Sidebar.</h4>
					<a href="<?php echo home_url( '/'  ); ?>wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php endif; ?><?php
					}
	            } elseif ( function_exists('bp_is_page') && bp_is_page(BP_BLOGS_SLUG) ) {
	                if ( is_active_sidebar( 'blogs-sidebar' ) ) {
	                    dynamic_sidebar( 'blogs-sidebar');
					} else { ?>
					<?php if( current_user_can('edit_theme_options') ) : ?>
					<div class="widget"><h4>BP Blogs Sidebar.</h4>
					<a href="<?php echo home_url( '/'  ); ?>wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php endif; ?><?php
					}
				} elseif( function_exists('bp_is_page') && bp_is_page(BP_ACTIVITY_SLUG) ) {
					if ( is_active_sidebar( 'activity-sidebar' ) ) {
						dynamic_sidebar( 'activity-sidebar');
					} else { ?>
					<?php if( current_user_can('edit_theme_options') ) : ?>
					<div class="widget"><h4>Activity Sidebar</h4>
					<a href="<?php echo home_url( '/'  ); ?>wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php endif; ?><?php
					}
				} elseif( is_front_page() ) {
					if ( is_active_sidebar( 'home-sidebar' ) ) {
						dynamic_sidebar( 'home-sidebar' );
					} else { ?>
					<?php if( current_user_can('edit_theme_options') ) : ?>
					<div class="widget"><h4>Home Sidebar.</h4>
					<a href="<?php echo home_url( '/'  ); ?>wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php endif; ?><?php
					}
				} elseif ( is_page() ) {
					if ( is_active_sidebar( 'page-sidebar' ) ) {
						dynamic_sidebar( 'page-sidebar');
					} else { ?>
					<?php if( current_user_can('edit_theme_options') ) : ?>
					<div class="widget"><h4>Page Sidebar.</h4>
					<a href="<?php echo home_url( '/'  ); ?>wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php endif; ?><?php
					}
				}
			} else {
				if ( is_active_sidebar( 'blog-sidebar' ) ) {
					dynamic_sidebar( 'blog-sidebar');
				} else { ?>
				<?php if( current_user_can('edit_theme_options') ) : ?>
					<div class="widget"><h4>Blog Sidebar.</h4>
					<a href="<?php echo home_url( '/'  ); ?>wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div>
					<?php endif; ?><?php
				}
			}
	}
}
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 */
function infinity_posted_on() {
	printf( __( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a>', infinity_text_domain ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', infinity_text_domain ), get_the_author() ) ),
		get_the_author()
	);
}

//
// Custom Conditionals
//
/**
 * Returns true if not in admin dir
 *
 * @package Infinity
 * @subpackage conditionals
 * @return boolean
 */
function is_not_admin() {
    return ( !is_admin() );
}

?>
