<?php
/**
 * Infinity Theme: author introduction box template
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage templates
 * @since 1.2
 */

// are introduction boxes supported?
if ( infinity_base_show_intro_box() ):

	// queue the first post, that way we know who the author is when we
	// try to get their name, URL, description, avatar, etc.
	if ( is_author() && have_posts() ):
		// set up this loop
		the_post();
		// load author box
		get_template_part( 'templates/parts/author-box' );
		// reset the loop so we don't break later queries
		rewind_posts();
	endif;

endif;