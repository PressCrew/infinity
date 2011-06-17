<?php
/**
 * Infinity Theme: main menu template
 *
 * @author Bowe Frankema <bowromir@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @subpackage templates
 * @since 1.0
 */
?>
<div id="main-menu-wrap">
<?php
	if ( infinity_option( 'infinity_base_buddypress_menu' ) == 2 ):
		// Load BuddyPress Menu Template */
		locate_template( array( '/functions/buddypress/menu.php' ), true );
	else: ?>
		<div id="base-menu" class="main-menu">
			<?php
				if ( has_nav_menu( 'primary-menu' ) ):
					/* if menu location 'primary-menu' exists then use custom menu */
					wp_nav_menu( array(
						'theme_location' => 'primary-menu',
						'menu_class' => 'sf-menu',
						'container' => '',
						'walker' => new menu_walker()
					));
				endif;
			?>
		</div>
<?php
	endif;
?>
</div>
