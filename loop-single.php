<?php
/**
 * Infinity Theme: loop single template
 *
 * The loop that displays single posts
 * 
 * @author Bowe Frankema <bowromir@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @subpackage templates
 * @since 1.0
 */

	if ( have_posts()):
		while ( have_posts() ):
			the_post();
			do_action( 'open_loop' );
?>
			<div class="item-options">
				<div class="alignleft">
					<?php
						next_posts_link( __( '&larr; Previous Entries', infinity_text_domain ) );
					?>
				</div>
				<div class="alignright">
					<?php
						previous_posts_link( __( 'Next Entries &rarr;', infinity_text_domain ) );
					?>
				</div>
			</div>
			<!-- the post -->
			<div class="post" id="post-<?php the_ID(); ?>">
				<?php
					do_action( 'open_loop_single' );
				?>
				<div class="post-content">
					<h1 class="posttitle">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', infinity_text_domain ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
					</h1>
					<p class="date">
						<span class="post-category">
							<?php
								the_category(', ');
								printf( __( 'by %s', infinity_text_domain ), get_the_author() );
							?>
						</span>
						<span class="time-posted">
							<?php
								print human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago';
							?>
						</span>
					</p>
					<?php
						do_action( 'before_single_entry' )
					?>
					<div class="entry">
						<?php
							do_action( 'open_single_entry' );
							the_content( __( 'Read the rest of this entry &rarr;', infinity_text_domain ) );
						?>
						<p class="post-tags">
							<?php
								the_tags( __( 'Tags: ', infinity_text_domain ), ', ', '<br />');
							?>
						</p>
						<?php
							infinity_get_template_part( 'author-box');
							wp_link_pages( array(
								'before' => __( '<p><strong>Pages:</strong> ', infinity_text_domain ),
								'after' => '</p>', 'next_or_number' => 'number')
							);
						?>
					</div>
				</div>
				<?php
					do_action( 'close_loop_single' );
				?>
			</div>
<?php
			comments_template('', true);
			do_action( 'close_loop' );
		endwhile;
	else: ?>
		<h1>
			<?php _e( 'Sorry, no posts matched your criteria.', infinity_text_domain ) ?>
		</h1>
<?php
		do_action( 'loop_not_found' );
	endif;
?>