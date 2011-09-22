<?php
/**
 * Infinity Theme: Post Meta Bottom Template
 *
 * @author Bowe Frankema <bowromir@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @subpackage templates
 * @since 1.0
 *
 * This template display the post tags attached to a post. You can hook into this section 
 * to add your own stuff as well!
 */
?>

<div class="post-meta-data post-bottom">
	<?php
		do_action( 'open_loop_post_meta_data_bottom' );
	?>
	<?php if ( has_tag() ) {?>
		<span class="post-tags">
			<?php
				the_tags( __( 'Tags: ', infinity_text_domain ), ' ', '');
			?>
		</span>
	<?php } ?>	
<?php
	do_action( 'close_loop_post_meta_data_bottom' );
?>
</div>
