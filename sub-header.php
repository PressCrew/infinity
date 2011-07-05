<?php
/**
 * Infinity Theme: sub header template
 *
 * @author Bowe Frankema <bowromir@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @subpackage templates
 * @since 1.0
 */

if ( infinity_option( 'infinity_base_sub_header' ) ): ?>
	<!-- featured image-->
	<div id="featured-image">
		<?php
			// Check if this is a post or page, if it has a thumbnail
			if ( is_singular() && has_post_thumbnail( $post->ID ) ):
				print get_the_post_thumbnail( $post->ID, 'featured-single' );
			else : ?>
				<img src="<?php echo infinity_option_image_url( 'infinity_base_sub_header_image', 'full' ); ?>">
		<?php
			endif;
		?>
	</div><!-- featured image-->
<?php endif; ?>
