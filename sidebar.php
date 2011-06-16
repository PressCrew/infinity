<?php
/**
 * Infinity Theme: sidebar template
 *
 * @author Bowe Frankema <bowromir@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @subpackage templates
 * @since 1.0
 */

	do_action( 'before_sidebar' );
?>
<!-- sidebar -->
<div class="grid_4" id="sidebar">
	<?php
		do_action( 'open_sidebar' );
		
		if ( is_page() ) {
			global $post;
			if ( function_exists('bp_is_member') && bp_is_member() ) {
				if ( is_active_sidebar( 'member-sidebar' ) ) {
					dynamic_sidebar( 'member-sidebar' );
				} else { ?>
					<p class="tips">Member Sidebar</p><?php
				}
           } elseif ( function_exists('bp_is_page') && bp_is_page(BP_GROUPS_SLUG) ) {
                if ( is_active_sidebar( 'groups-sidebar' ) ) {
                    dynamic_sidebar( 'groups-sidebar');
				} else { ?>
                    <p class="tips">Group Sidebar</p><?php
				}
            } elseif ( function_exists('bp_is_page') && bp_is_page(BP_FORUMS_SLUG) ) {
                if ( is_active_sidebar( 'forums-sidebar' ) ) {
                    dynamic_sidebar( 'forums-sidebar');
				} else { ?>
                    <p class="tips">Forums</p><?php
				}
            } elseif ( function_exists('bp_is_page') && bp_is_page(BP_BLOGS_SLUG) ) {
                if ( is_active_sidebar( 'blogs-sidebar' ) ) {
                    dynamic_sidebar( 'blogs-sidebar');
				} else { ?>
                    <p class="tips">Blogs</p><?php
				}
			} elseif( is_single() ) {
				if ( is_active_sidebar( 'single-sidebar' ) ) {
					dynamic_sidebar( 'single-sidebar');
				} else { ?>
					<p class="tips">Single</p><?php
				}
			} elseif( is_front_page() ) {
				if ( is_active_sidebar( 'activity-sidebar' ) ) {
					dynamic_sidebar( 'activity-sidebar' );
				} else { ?>
					<p class="tips">Homepage</p><?php
				}
			} else {
				if ( is_active_sidebar( 'page-sidebar' ) ) {
					dynamic_sidebar( 'page-sidebar');
				} else { ?>
					<p class="tips">Page</p><?php
				}
			}
		} else {
			if ( is_active_sidebar( 'blog-sidebar' ) ) {
				dynamic_sidebar( 'blog-sidebar');
			} else { ?>
				<p class="tips">Blog Sidebar</p><?php
			}
		}
		
		do_action( 'close_sidebar' );
	?>
</div><!-- sidebar -->
<?php
	do_action( 'after_sidebar' );
?>
