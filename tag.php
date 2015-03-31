<?php
/**
 * Infinity Theme: tag template
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
		do_action( 'open_tag' );
	?>
	<div class="page" id="blog-tag">
		<?php
			// load intro boxes part
			get_template_part( 'templates/parts/intro-box' );
			// load tag loop
			get_template_part( 'templates/loops/loop', 'tag' );
		?>
	</div>
	<?php
		do_action( 'close_tag' );
		do_action( 'close_content' );
	?>
</div><!-- #content -->
<?php
	get_sidebar();
	get_footer();
