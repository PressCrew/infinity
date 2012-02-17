<?php
/**
 * Infinity Theme: footer template
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

		<?php
			do_action( 'close_main_wrap' );
		?>
		</div>
		<div class="footer-wrap <?php do_action( 'footer_wrap_class' ); ?>">
		<?php
			do_action( 'open_footer_wrap' );
		?>
		<!-- begin footer -->
		<footer id="footer" role="contentinfo">
			<?php
				do_action( 'open_footer' );
			?>
			
			<?php if ( is_active_sidebar( 'Footer Left' ) || is_active_sidebar( 'Footer Middle' ) || is_active_sidebar( 'Footer Right' ) ) : ?>
				<!-- footer widgets -->
				<div class="footer-widgets">
					<div class="<?php do_action( 'footer_widget_class' ); ?> footer-widget" id="footer-widget-left">
						<?php
							dynamic_sidebar( 'Footer Left' );
						?>
					</div>
					<div class="<?php do_action( 'footer_widget_class' ); ?> footer-widget" id="footer-widget-middle">
						<?php
							dynamic_sidebar( 'Footer Middle' );
						?>
					</div>
					<div class="<?php do_action( 'footer_widget_class' ); ?> footer-widget" id="footer-widget-right">
						<?php
							dynamic_sidebar( 'Footer Right' );
						?>
					</div>
				</div>
				<div style="clear: both;"></div>
			<?php endif; // end primary widget area ?>
			<div id="powered-by">
				<div id="footer-info" class="grid_16 alpha">
				<?php
					// Load Footer Menu only if it's enabled
					if ( current_theme_supports( 'infinity-footer-menu-setup' ) ) :
						infinity_get_template_part( 'templates/parts/footer-menu', 'footer' );
					endif;
				?>
				</div>
				<div id="copyright-info" class="grid_8 omega">	
					<?php echo infinity_option_get( 'infinity-core-options-footer_text' ); ?>
				</div>
				<div style="clear: both;"></div>
			</div>
			<?php
				do_action( 'close_footer' );
			?>
		</footer>
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