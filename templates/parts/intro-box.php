<?php
/**
 * Infinity Theme: introduction boxes template
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage templates
 * @since 1.0
 */

// This part mostly exists for backwards compatibility, but
// could be very useful in sub-templates where the post type
// varies depending on which parent template loads it.

// are introduction boxes supported?
if ( infinity_base_show_intro_box() ):

	// on a category page?
	if ( is_category() ):

		// show category archives
		get_template_part( 'templates/parts/intro-box', 'category' );
	
	// on a tag page?
	elseif ( is_tag() ):
		
		// show tag archives
		get_template_part( 'templates/parts/intro-box', 'tag' );
		
	// on author page?
	elseif ( is_author() ):
		
		// show author box
		get_template_part( 'templates/parts/intro-box', 'author' );
		
	// on any archive page?
	elseif ( is_day() || is_month() || is_year() ):

		// show post archives
		get_template_part( 'templates/parts/intro-box', 'archive' );
		
	endif;

endif;