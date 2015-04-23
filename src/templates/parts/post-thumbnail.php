<?php
/**
 * Infinity Theme: Post Thumbnail
 *
 * The Post Thumbnail Template part
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage templates
 * @since 1.0
 */

// show the post thumb?
if ( current_theme_supports( 'post-thumbnails' ) && has_post_thumbnail() ):

	// yep, get settings
	$thumbheight = infinity_option_get( 'thumbs.image-height' );
	$thumbwidth = infinity_option_get( 'thumbs.image-width' );

	// spit out the thumb ?>
	<figure class="postthumb">
		<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'infinity' ); ?> <?php the_title_attribute(); ?>"><?php the_post_thumbnail( array( $thumbwidth, $thumbheight ) ); ?></a>
	</figure>
	<?php

endif;