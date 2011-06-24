<?php
/**
 * Infinity Theme: loop page template
 *
 * The loop that displays a page
 * 
 * @author Bowe Frankema <bowromir@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @subpackage templates
 * @since 1.0
 */

	if ( have_posts() ):
		while ( have_posts() ):
			the_post();
			do_action( 'open_loop' );
?>
		<h1 class="pagetitle">
			<?php
				the_title();
				edit_post_link(' ✍','',' ');
			?>
		</h1>
		<!-- show page thumb -->
		<div id="page-thumb">
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', infinity_text_domain ) ?> <?php the_title_attribute(); ?>"><?php the_post_thumbnail('post-image'); ?></a>
		</div>
		<!-- the post -->
		<div class="post" id="post-<?php the_ID(); ?>">
			<?php
				do_action( 'open_loop_page' );
			?>
			<div class="entry">
				<?php
					the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'buddypress' ) );
					wp_link_pages( array( 'before' => __( '<p><strong>Pages:</strong> ', 'buddypress' ), 'after' => '</p>', 'next_or_number' => 'number'));
					edit_post_link( __( 'Edit this entry.', 'buddypress' ), '<p>', '</p>');
				?>
			</div>
			<?php
				do_action( 'close_loop_page' );
			?>
		</div>
<?php
		do_action( 'close_loop' );
		endwhile;
	endif;
?>
