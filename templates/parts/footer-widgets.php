<?php
/**
 * Infinity Theme: Footer Menu Widgets
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
<div class="footer-widgets">			
	<?php if ( is_active_sidebar( 'Footer Left' ) ) : ?>
		<!-- footer widgets -->
			<div class="footer-widget eight columns" id="footer-widget-left">
				<?php
					dynamic_sidebar( 'Footer Left' );
				?>
			</div>
	<?php endif;?>
	<?php if ( is_active_sidebar( 'Footer Middle' ) ) : ?>
			<div class="footer-widget eight columns" id="footer-widget-middle">
				<?php
					dynamic_sidebar( 'Footer Middle' );
				?>
			</div>
	<?php endif; ?>
	<?php if ( is_active_sidebar( 'Footer Right' ) ) : ?>
			<div class="footer-widget eight columns" id="footer-widget-right">
				<?php
					dynamic_sidebar( 'Footer Right' );
				?>
			</div>
	<?php endif; ?>
</div>