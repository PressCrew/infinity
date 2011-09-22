<?php
/**
 * Infinity Theme: loop template
 *
 * The loop that displays posts
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
		<!-- post -->
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php
				do_action( 'open_loop_post' );
			?>
			<!-- post-content -->
			<div class="post-content">
				<?php
					do_action( 'open_loop_post_content' );
				?>
				<!-- post title -->
				<h2 class="posttitle">
					<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', infinity_text_domain ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
					<?php edit_post_link(' ✍','',' ');?>
				</h2>
				<?php
				infinity_get_template_part( 'templates/parts/post-meta-top');	
				?>				
				<?php
				do_action( 'before_post_thumb' );
				?>
				<!-- show the avatar? -->
				<div class="entry">
				<?php
				infinity_get_template_part( 'templates/parts/post-thumbnail');	
				?>	
					<div class="post-author-box">
						<?php
							echo get_avatar( get_the_author_meta( 'user_email' ), '50' );
						?>
					</div>
					<?php
						do_action( 'before_loop_content' );
						the_content( __( 'Read More', 'infinity' ) );
						wp_link_pages( array( 'before' => '<div class="page-link">' . __( '<span>Pages:</span>', infinity_text_domain ), 'after' => '</div>' ) ); 
						do_action( 'after_loop_content' );
					?>
				</div>
				
				<?php
					infinity_get_template_part( 'templates/parts/post-meta-bottom');	
				?>
				
				<?php
					do_action( 'close_loop_post_content' );
				?>
			</div><!-- post-content -->
			<?php
				do_action( 'close_loop_post' );
			?>
		</div><!-- post -->
	<?php
		do_action( 'close_loop' );
		endwhile;
		infinity_base_paginate();
	else:
?>
		<h2 class="center">
			<?php _e( 'Not Found', infinity_text_domain ) ?>
		</h2>
<?php
		infinity_get_search_form();
		do_action( 'loop_not_found' );
	endif;
?>
