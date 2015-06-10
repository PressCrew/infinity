<?php
/**
 * Infinity Theme: loop single template
 *
 * The loop that displays single posts
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage templates
 * @since 1.0
 */

// have single post ?
if ( have_posts()):
	// loop single post
	while ( have_posts() ):
		// set up this loop
		the_post();
		// open loop action
		do_action( 'open_loop' );
		// render post markup ?>
		<!-- the post -->
		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			<div class="post-content">
				<?php
					get_template_part( 'templates/parts/post-avatar');
				?>
				<h1 class="post-title">
					<?php the_title(); ?>
					<?php edit_post_link();?>
				</h1>
				<?php
					do_action( 'open_loop_single' );
					get_template_part( 'templates/parts/post-thumbnail');
					get_template_part( 'templates/parts/post-meta-top');
					do_action( 'before_single_entry' )
				?>
				<div class="entry">
					<?php
						do_action( 'open_single_entry' );
						the_content( __( 'Read the rest of this entry &rarr;', 'infinity-engine' ) );
					?>
					<div class="clear"></div>
					<?php
						wp_link_pages( array(
							'before' => '<p><strong>' . __( 'Pages:', 'infinity-engine' ) . '</strong> ',
							'after' => '</p>', 'next_or_number' => 'number'
						) );
						do_action( 'close_single_entry' );
					?>
				</div>
				<?php
					do_action('after_single_entry');
					get_template_part('templates/parts/post-meta-bottom');
					get_template_part( 'templates/parts/author-box');
				?>
			</div>
			<?php
				do_action( 'close_loop_single' );
			?>
		</div>
		<?php
			comments_template('', true);
			do_action( 'close_loop' );

	// end loop
	endwhile;

// no posts found
else:
	// show no posts found message?>
	<h1>
		<?php _e( 'Sorry, no posts matched your criteria.', 'infinity-engine' ); ?>
	</h1>
	<?php
		do_action( 'loop_not_found' );

// all done
endif;
