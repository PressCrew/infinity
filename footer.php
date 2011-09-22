<?php
/**
 * Infinity Theme: footer template
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

		<?php
			do_action( 'close_main_wrap' );
		?>
		</div>
		<div class="footer-wrap">
		<?php
			do_action( 'open_footer_wrap' );
		?>
		<!-- begin footer -->
		<div id="footer">
			<?php
				do_action( 'open_footer' );
			?>
			<!-- footer widgets -->
			<div class="footer-widgets grid_24">
				<div class="grid_8 footer-widget alpha" id="footer-widget-left">
					<?php
						dynamic_sidebar( 'Footer Left' );
					?>
				</div>
				<div class="grid_8 footer-widget" id="footer-widget-middle">
					<?php
						dynamic_sidebar( 'Footer Middle' );
					?>
				</div>
				<div class="grid_8 footer-widget omega" id="footer-widget-right">
					<?php
						dynamic_sidebar( 'Footer Right' );
					?>
				</div>
			</div>
			<div style="clear: both;"></div>
			<div class="footer-menu" role="navigation">
					<?php wp_nav_menu( array( 'theme_location' => 'footer-menu' ) ); ?>	
			</div>
			<div id="footer-info">
				Theme: <a href="http://infinity.presscrew.com">Infinity</a><span class="footer-logo"></span>	<a rel="generator" href="http://infinity.presscrew.com">More freedom to create</a>. 
			</div>
			<?php
				do_action( 'close_footer' );
			?>
		</div>
		<?php
			do_action( 'close_footer_wrap' );
		?>
		</div><!-- close container -->
	</div>
<?php
	do_action( 'close_body' );
	wp_footer();
?>

</body>
</html>