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
				</h2>
				<!-- post date -->
				<p class="date">
					<span class="post-category">
						<?php
							the_category(', ');
							printf( __( 'by %s', infinity_text_domain ), get_the_author() );
						?>
					</span>
					<span class="time-posted">
						<?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?>
					</span>
				</p>
				<?php
					do_action( 'before_post_thumb' );
				?>
				<!-- show the post thumb? -->
				<div class="postthumb">
					<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', infinity_text_domain ) ?> <?php the_title_attribute(); ?>"><?php the_post_thumbnail('post-image'); ?></a>
				</div>
				<!-- show the avatar? -->
				<div class="entry">
					<div class="post-author-box">
						<?php
							echo get_avatar( get_the_author_meta( 'user_email' ), '50' );
						?>
					</div>
					<?php
						do_action( 'before_loop_content' );
						the_content( __( 'Read More', 'infinity' ) );
						do_action( 'after_loop_content' );
					?>
				</div>
				<p class="postmetadata">
					<?php
						do_action( 'open_loop_post_meta_data' );
					?>
					<span class="tags">
						<?php
							the_tags( __( 'Tags: ', infinity_text_domain ), ', ', '<br />');
						?>
					</span>
					<span class="comments">
						<?php
							comments_popup_link(
								__( 'No Comments &#187;', infinity_text_domain ),
								__( '1 Comment &#187;', infinity_text_domain ),
								__( '% Comments &#187;', infinity_text_domain )
							);
						?>
					</span>
					<?php
						do_action( 'close_loop_post_meta_data' );
					?>
				</p>
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
		infinity_get_template_part( 'page-navigation' );
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
