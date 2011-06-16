<?php
/**
 * Infinity Theme: index template
 *
 * @author Bowe Frankema <bowromir@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @subpackage templates
 * @since 1.0
 */

	infinity_get_header();
?>
	<div class="grid_8" id="content">
		<?php
			do_action( 'open_content' );
			do_action( 'open_home' );
		?>
		<div id="home-page" role="main" <?php post_class(); ?>>
			<?php
				infinity_get_template_part( 'introduction-boxes', 'index' );
				infinity_get_template_part( 'loop', 'index' );
			?>
		</div>
		<?php
			do_action( 'close_home' );
			do_action( 'close_content' );
		?>
	</div>
<?php
	infinity_get_sidebar();
	infinity_get_footer();
?>
