<?php
/**
 * Infinity Theme: main menu template
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage templates
 * @since 1.0
 */
?>
<div id="main-menu-wrap" role="navigation">
	<div class="base-menu main-menu">
		<?php
			do_action('open_main_menu');
			infinity_base_nav_menu( 'main-menu' );
			do_action('close_main_menu');
		?>
	</div>
</div>