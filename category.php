<?php
/**
 * Infinity Theme: category template
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
	<div id="content" role="main">
		<?php
			do_action( 'open_content' );
			do_action( 'open_category' );
		?>
		<div class="page" id="blog-category">
			<?php
				infinity_get_template_part( 'templates/parts/introduction-boxes' );
				infinity_get_template_part( 'templates/loops/loop', 'category' );
			?>
		</div>
		<?php
			do_action( 'close_category' );
			do_action( 'close_content' );
		?>
	</div>
<?php
	infinity_get_sidebar();
	infinity_get_footer();
?>
