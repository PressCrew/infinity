<?php
/**
 * Infinity Theme: sidebar template
 *
 * @author Bowe Frankema <bowromir@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @subpackage templates
 * @since 1.0
 */

	do_action( 'before_sidebar' );
?>

<div id="sidebar">
	<div id="inner-sidebar">
	<!-- sidebar -->
		<?php
			do_action( 'open_sidebar' );?>
			
		<?php	
			// Load Sidebars
			infinity_base_sidebars();
			do_action( 'close_sidebar' );
		?>
	
	<?php
		do_action( 'after_sidebar' );
	?>
	</div>
</div>
