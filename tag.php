<?php
/**
 * The template for displaying Tag Archive pages.
 *
 * @package themes
 * @subpackage templates
 */

infinity_get_header(); ?>

		<div id="container">
			<div id="content" role="main">

				<h1 class="page-title"><?php
					printf( __( 'Tag Archives: %s', infinity_text_domain ), '<span>' . single_tag_title( '', false ) . '</span>' );
				?></h1>

<?php
/* Run the loop for the tag archive to output the posts
 * If you want to overload this in a child theme then include a file
 * called loop-tag.php and that will be used instead.
 */
infinity_get_template_part( 'loop', 'tag' );
?>
			</div><!-- #content -->
		</div><!-- #container -->

<?php
infinity_get_sidebar();
infinity_get_footer();
?>
