<?php
/**
 * Infinity Theme: sub menu template
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
<div id="sub-menu-wrap">
<?php
	if ( infinity_option( 'infinity_base_buddypress_menu' ) == 4 ):
		/* Load BuddyPress Menu Template */
		locate_template( array( '/functions/buddypress/menu.php' ), true );
	else:
?>
		<div id="primary-nav" class="sub-menu">
			<?php
				if ( has_nav_menu( 'sub-menu' ) ):
					/* if menu location 'primary-menu' exists then use custom menu */
					wp_nav_menu( array(
						'theme_location' => 'sub-menu',
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