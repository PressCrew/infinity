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

// show if main menu is set or neither other top menu has been set
if (
	has_nav_menu( 'main-menu' ) ||
	(
		!has_nav_menu( 'over-menu' ) &&
		!has_nav_menu( 'sub-menu' )
	)
):
	// show main menu ?>
	<div id="main-menu-wrap" role="navigation">
		<nav class="base-menu main-menu">
			<?php
				do_action('open_main_menu');
				infinity_base_nav_menu( 'main-menu' );
				do_action('close_main_menu');
			?>
		</nav>
	</div>
	<?php
endif;