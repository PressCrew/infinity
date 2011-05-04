<?php
/**
 * Template Name: One column, no sidebar
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package themes
 * @subpackage templates
 */

infinity_get_header(); ?>

		<div id="container" class="one-column">
			<div id="content" role="main">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', infinity_text_domain ), 'after' => '</div>' ) ); ?>
						<?php edit_post_link( __( 'Edit', infinity_text_domain ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .entry-content -->
				</div><!-- #post-## -->

				<?php infinity_comments_template( '', true ); ?>

<?php endwhile; ?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php infinity_get_footer(); ?>
