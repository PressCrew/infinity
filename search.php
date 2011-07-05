<?php
/**
 * Infinity Theme: search results template
 *
 * This template is a fork of the same template from
 * the Twenty Ten theme which ships with WordPress.
 *
 * @package infinity
 * @subpackage templates
 * @since 1.0
 */

infinity_get_header(); ?>

		<div id="container">
			<div id="content" role="main">

<?php if ( have_posts() ) : ?>
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', infinity_text_domain ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				<?php
				/* Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called loop-search.php and that will be used instead.
				 */
				 infinity_get_template_part( 'loop', 'search' );
				?>
<?php else : ?>
				<div id="post-0" class="post no-results not-found">
					<h2 class="entry-title"><?php _e( 'Nothing Found', infinity_text_domain ); ?></h2>
					<div class="entry-content">
						<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', infinity_text_domain ); ?></p>
						<?php infinity_get_search_form(); ?>
					</div><!-- .entry-content -->
				</div><!-- #post-0 -->
<?php endif; ?>
			</div><!-- #content -->
		</div><!-- #container -->

<?php infinity_get_sidebar(); ?>
<?php infinity_get_footer(); ?>
