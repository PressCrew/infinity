<?php
/**
 * Infinity Theme: template tags
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @subpackage includes
 * @since 1.0
 */

/**
 * Print a basic title
 */
function infinity_base_title()
{
	global $paged, $s;

	if ( function_exists( 'is_tag' ) && is_tag() ) {
		single_tag_title( "Tag Archive for &quot;" );
		print '&quot; | ';
	} elseif (is_archive()) {
		wp_title('');
		print ' Archive | ';
	} elseif (is_search()) {
		print 'Search for &quot;' . wp_specialchars( $s ) . '&quot; | ';
	} elseif ( !is_404() && is_single() || is_page() ) {
		wp_title('');
		print ' | ';
	} elseif ( is_404() ) {
		print 'Not Found | ';
	}

	if ( is_home() ) {
		bloginfo('name');
		print ' | ';
		bloginfo('description');
	} else {
		bloginfo('name');
	}

	if ( $paged > 1 ) {
		print ' | page '. $paged;
	}
}

/**
 * Show author box (if on an author page)
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
				infinity_get_template_part( 'author-box' );
			endif;

			// reset the loop so we don't break later queries
			rewind_posts();
		endif;
	endif;
}

/* Show sidebars based on page type (including BP components) */
function infinity_base_sidebars()
{
		if ( is_page() ) {
			global $post;
			if ( function_exists('bp_is_member') && bp_is_member() ) {
				if ( is_active_sidebar( 'member-sidebar' ) ) {
					dynamic_sidebar( 'member-sidebar' );
				} else { ?>
				<div class="widget"><h4>BP Member Sidebar.</h4>
				<a href="<?php echo home_url( '/'  ); ?>/wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php
				}
           } elseif ( function_exists('bp_is_page') && bp_is_page(BP_GROUPS_SLUG) ) {
                if ( is_active_sidebar( 'groups-sidebar' ) ) {
                    dynamic_sidebar( 'groups-sidebar');
				} else { ?>
				<div class="widget"><h4>BP Group Sidebar.</h4>
				<a href="<?php echo home_url( '/'  ); ?>/wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php
				}
            } elseif ( function_exists('bp_is_page') && bp_is_page(BP_FORUMS_SLUG) ) {
                if ( is_active_sidebar( 'forums-sidebar' ) ) {
                    dynamic_sidebar( 'forums-sidebar');
				} else { ?>
				<div class="widget"><h4>BP Forums Sidebar.</h4>
				<a href="<?php echo home_url( '/'  ); ?>/wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php
				}
            } elseif ( function_exists('bp_is_page') && bp_is_page(BP_BLOGS_SLUG) ) {
                if ( is_active_sidebar( 'blogs-sidebar' ) ) {
                    dynamic_sidebar( 'blogs-sidebar');
				} else { ?>
				<div class="widget"><h4>BP Blogs Sidebar.</h4>
				<a href="<?php echo home_url( '/'  ); ?>/wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php
				}
			} elseif( is_single() ) {
				if ( is_active_sidebar( 'single-sidebar' ) ) {
					dynamic_sidebar( 'single-sidebar');
				} else { ?>
				<div class="widget"><h4>Single Posts Sidebar.</h4>
				<a href="<?php echo home_url( '/'  ); ?>/wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php
				}
			} elseif( is_front_page() ) {
				if ( is_active_sidebar( 'activity-sidebar' ) ) {
					dynamic_sidebar( 'activity-sidebar' );
				} else { ?>
				<div class="widget"><h4>Home Sidebar.</h4>
				<a href="<?php echo home_url( '/'  ); ?>/wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php
				}
			} else {
				if ( is_active_sidebar( 'page-sidebar' ) ) {
					dynamic_sidebar( 'page-sidebar');
				} else { ?>
				<div class="widget"><h4>Page Sidebar.</h4>
				<a href="<?php echo home_url( '/'  ); ?>wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php
				}
			}
		} else {
			if ( is_active_sidebar( 'blog-sidebar' ) ) {
				dynamic_sidebar( 'blog-sidebar');
			} else { ?>
				<div class="widget"><h4>Blog Sidebar.</h4>
				<a href="<?php echo home_url( '/'  ); ?>/wp-admin/widgets.php" title="Add Widgets">Add Widgets</a></div><?php
			}
		}
}

//
// Custom Conditionals
//

function not_admin() {
    return ( !is_admin() );
}

function home_slider() {
	return ( infinity_option( 'infinity_base_slider' ) == true && is_home() );
}

?>
