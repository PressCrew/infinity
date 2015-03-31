<?php
/**
 * Infinity Theme: sub menu template
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage templates
 * @since 1.0
 */

// is sub menu set?
if ( has_nav_menu( 'sub-menu' ) ):
	// show sub menu ?>
	<div id="sub-menu-wrap" role="navigation">
		<nav class="base-menu sub-menu">
			<?php
				do_action('open_sub_menu');
				infinity_base_nav_menu( 'sub-menu' );
				do_action('close_sub_menu');
			?>
		</nav>
	</div>
	<?php
endif;