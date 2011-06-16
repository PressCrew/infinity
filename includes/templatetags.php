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
