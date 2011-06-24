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
			do_action( 'close_container' );
		?>
		</div> <!-- close container -->
	<?php
		do_action( 'close_main_wrap' );
	?>
	</div><!-- close main-wrap -->

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
			<div class="grid_24" id="footer-full">
				<div class="grid_8 footer-widget" id="footer-widget-left">
					<?php
						dynamic_sidebar( 'Footer Left' );
					?>
				</div>
				<div class="grid_8 footer-widget" id="footer-widget-left">
					<?php
						dynamic_sidebar( 'Footer Middle' );
					?>
				</div>
				<div class="grid_8 footer-widget" id="footer-widget-right">
					<?php
						dynamic_sidebar( 'Footer Right' );
					?>
				</div>
			</div>
			<?php
				do_action( 'close_footer' );
			?>
		</div>
		<?php
			do_action( 'close_footer_wrap' );
		?>
	</div>
<?php
	do_action( 'close_body' );
	wp_footer();
?>

</body>
</html>