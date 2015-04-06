<?php
/**
 * Template Name: Sidebar Left Template
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage templates
 * @since 1.0
 */

	get_header();
?>
<div id="content" role="main" class="<?php do_action( 'content_class' ); ?>">
	<?php
		do_action( 'open_content' );
		do_action( 'open_page' );
	?>
	<div class="page-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php
			get_template_part( 'templates/loops/loop', 'page' );
		?>
	</div><!-- .page -->
	<?php
		do_action( 'close_page' );
		do_action( 'close_content' );
	?>
</div>
<?php
	get_sidebar();
	get_footer();
