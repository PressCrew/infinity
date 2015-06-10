<?php
/**
 * Infinity Theme: loop attachment
 *
 * The loop that displays attachments
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage templates
 * @since 1.0
 */

// are there attachments?
if ( have_posts() ):
	// loop all found attachments
	while ( have_posts() ):
		// set up this loop
		the_post();
		// do open loop action
		do_action( 'open_loop' );
	?>
		<!-- the post -->
		<article class="post" id="post-<?php the_ID(); ?>">
			<div class="post-content">
				<header>
					<h1 class="post-title">
						<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'infinity-engine' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
					</h1>
				</header>
				<?php
					do_action( 'open_loop_single' );
					get_template_part( 'templates/parts/post-meta-top');
					do_action( 'before_single_entry' );
				?>
				<div class="entry">
					<a href="#">
						<figure class="attachment-image">
							<?php
								// print the attachment image
								echo wp_get_attachment_image( $post->ID, 'large', false, array( 'class' => 'size-large aligncenter' ) );
							?>
						</figure>
					</a>
					<div class="entry-caption">
						<?php
							// is there an excerpt?
							if ( !empty( $post->post_excerpt ) ):
								// print the excerpt
								the_excerpt();
							endif;
						?>
					</div>
					<?php
						// print the content
						the_content();
					?>
					<footer class="post-meta-data post-bottom">
						<?php
							do_action( 'open_loop_post_meta_data_bottom' );
						?>
						<span class="post-tags"><?php
							// is the attachment an image?
							if ( wp_attachment_is_image() ):
								// get meta data
								$metadata = wp_get_attachment_metadata();
								// print link to full size image
								printf(
									__( 'Full size is %s pixels', 'infinity-engine' ),
									sprintf(
										'<a href="%1$s" title="%2$s">%3$s &times; %4$s</a>',
										wp_get_attachment_url(),
										esc_attr( __( 'Link to full size image', 'infinity-engine' ) ),
										$metadata['width'],
										$metadata['height']
									)
								);
							endif;
						?>
						</span>
						<span class="post-comments">
							<?php
								// show comments popup
								comments_popup_link(
									__( 'No Comments', 'infinity-engine' ),
									__( '1 Comment', 'infinity-engine'),
									__( '% Comments', 'infinity-engine')
								);
							?>
						</span>
						<?php
							do_action( 'close_loop_post_meta_data_bottom' );
						?>
					</footer>
					<?php
						do_action( 'close_loop_single' );

						// print pages links
						wp_link_pages( array(
							'before' => __( '<p><strong>Pages:</strong> ', 'infinity-engine' ),
							'after' => '</p>', 'next_or_number' => 'number')
						);

						// load author box part
						get_template_part( 'templates/parts/author-box');
					?>
				</div>
			</div>
			<?php
				do_action( 'close_loop_single' );
			?>
		</article>
		<?php
			// load comments
			comments_template('', true);
			do_action( 'close_loop' );

	// end loop
	endwhile;

// no posts found
else:

	// display no posts message ?>
	<h1>
		<?php
			_e( 'Sorry, no posts matched your criteria.', 'infinity-engine' );
		?>
	</h1>
	<?php
		do_action( 'loop_not_found' );

// all done
endif;
