<?php
/**
 * Infinity Theme: Post Meta Bottom Template
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage templates
 * @since 1.0
 *
 * This template display the post tags attached to a post. You can hook into this section
 * to add your own stuff as well!
 */
?>

<footer class="post-meta-data post-bottom">
	<?php
		do_action( 'open_loop_post_meta_data_bottom' );

		// have a tag?
		if ( has_tag() ):
			// show the tags ?>
			<span class="post-tags">
				<?php
					// print the tag links
					the_tags( __( 'Tags: ', 'infinity-engine' ), ' ', '' );
				?>
			</span>
			<?php
		endif;

		do_action( 'close_loop_post_meta_data_bottom' );
	?>
</footer>
