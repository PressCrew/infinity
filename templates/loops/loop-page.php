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
		<?php
		infinity_get_template_part( 'templates/parts/post-thumbnail');	
		?>	
		<!-- the post -->
		<div class="post" id="post-<?php the_ID(); ?>">
			<?php
				do_action( 'open_loop_page' );
			?>
			<div class="entry">
				<?php
					the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', infinity_text_domain ) );
					wp_link_pages( array( 'before' => __( '<p><strong>Pages:</strong> ', infinity_text_domain ), 'after' => '</p>', 'next_or_number' => 'number'));
					edit_post_link( __( 'Edit this entry.', infinity_text_domain ), '<p>', '</p>');
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
