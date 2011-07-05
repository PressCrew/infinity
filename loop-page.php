<?php
/**
 * Infinity Theme: page loop template
 *
 * This template is a fork of the same template from
 * the Twenty Ten theme which ships with WordPress.
 *
 * @package infinity
 * @subpackage templates
 * @since 1.0
 */
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php if ( is_front_page() ) { ?>
						<h2 class="entry-title"><?php the_title(); ?></h2>
					<?php } else { ?>
						<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php } ?>

					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', infinity_text_domain ), 'after' => '</div>' ) ); ?>
						<?php edit_post_link( __( 'Edit', infinity_text_domain ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .entry-content -->
				</div><!-- #post-## -->

				<?php infinity_comments_template( '', true ); ?>

<?php endwhile; // end of the loop. ?>