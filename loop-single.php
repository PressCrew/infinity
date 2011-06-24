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
						<?php edit_post_link(' ✍','',' ');?>
					</h1>
					<p class="post-meta-data">
					<?php
						do_action( 'open_loop_post_meta_data_top' );
					?>					
						<span class="post-author">
							<?php
								the_author_link();
							?>
						</span>
						<span class="post-category">
							<?php 
								the_category(', ') 
							?>						
						</span>
						<span class="time-posted">
							<?php
								print human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago';
							?>
						</span>
						<span class="post-comments">
							<?php
								comments_popup_link(
									__( 'No Comments &#187;', infinity_text_domain ),
									__( '1 Comment &#187;', infinity_text_domain ),
									__( '% Comments &#187;', infinity_text_domain )
								);
							?>
						</span>
					<?php
						do_action( 'close_loop_post_meta_data_top' );
					?>

					</p>
					<?php
						do_action( 'before_single_entry' )
					?>
					<div class="entry">
						<?php
							do_action( 'open_single_entry' );
							the_content( __( 'Read the rest of this entry &rarr;', infinity_text_domain ) );
							wp_link_pages( array( 'before' => '<div class="page-link">' . __( '<span>Pages:</span>', 'twentyeleven' ), 'after' => '</div>' ) ); 
						?>
						<p class="post-meta-data post-bottom">
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
						</p>
						<?php get_template_part('social-sharing'); ?>
						<?php
							wp_link_pages( array(
								'before' => __( '<p><strong>Pages:</strong> ', infinity_text_domain ),
								'after' => '</p>', 'next_or_number' => 'number')
							);
							infinity_get_template_part( 'author-box');	
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