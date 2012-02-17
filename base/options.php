<?php
/**
 * Add Core Options
 *
 * @package Infinity
 * @subpackage base
 * @todo move this to a feature extension, no direct plugin support
 */


/**
 * Insert Google Analyitcs code
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_google_analytics() { { ?>
	<!-- html -->
		<?php echo infinity_option_get( 'infinity-core-options-google_analytics' ); ?>
	<!-- end -->
	<?php }} 
// Hook into action
add_action('close_body','infinity_google_analytics');

/**
 * Custom Favicon
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_custom_favicon() { { ?>
	<?php if ( infinity_option_get( 'infinity-core-options-custom_favicon' ) ): ?>
		<link rel="shortcut icon" type="image/png" href="<?php echo infinity_option_image_url( 'infinity-core-options-custom_favicon', full ); ?>" />
	<?php else: ?>
		<link rel="shortcut icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.png" />
	<?php endif; ?>
	<?php }} 
// Hook into action
add_action('wp_head','infinity_custom_favicon'); 

if ( current_theme_supports( 'infinity-grid-style' ) ) {
	/**
	 * Content Class: Let user pick a sidebar and content width through a theme options
	 *
	 * @package Infinity
	 * @subpackage base
	 */
	function infinity_content_class() { { ?>
			<?php if ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_17" ): ?>
				class="grid_17"
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_20" ): ?>
				class="grid_20"
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_19" ): ?>
				class="grid_19"
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_18" ): ?>
				class="grid_18"
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_16" ): ?>
				class="grid_16"
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_15" ): ?>
				class="grid_15"
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_14" ): ?>
				class="grid_14"
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_12" ): ?>
				class="grid_12"
			<?php endif; ?>
		<?php }} 
	// Hook into action
	add_action('content_class','infinity_content_class');
	
	/**
 	* Sidebar Class: Apply Sidebar Grid Class based on Core Theme Options
 	*
 	* @package Infinity
 	* @subpackage base
 	*/
	function infinity_sidebar_class() { { ?>
			<?php if ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_17" ): ?>
				class="grid_7"
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_20" ): ?>
				class="grid_4"
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_19" ): ?>
				class="grid_5"
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_18" ): ?>
				class="grid_6"
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_16" ): ?>
				class="grid_8"
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_15" ): ?>
				class="grid_9"
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_14" ): ?>
				class="grid_10"
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_12" ): ?>
				class="grid_12"
			<?php endif; ?>
		<?php }} 
	// Hook into action
	add_action('sidebar_class','infinity_sidebar_class');
	 
	/**
 	* Sidebar Class: Still Apply Grid Classes with jQuery even if content_class and sidebar_class do actions 
 	* Are not added to custom templates (Most notably BuddyPress) 
 	* @package Infinity
 	* @subpackage base
 	*/
	function infinity_grid_fallback() { { ?>
		<script>
		jQuery(document).ready(function() {
				<?php if ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_17" ): ?>
					jQuery('div#content').addClass('grid_17');
				<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_20" ): ?>
					jQuery('div#content').addClass('grid_20');
				<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_19" ): ?>
					jQuery('div#content').addClass('grid_19');
				<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_18" ): ?>
					jQuery('div#content').addClass('grid_18');
				<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_16" ): ?>
					jQuery('div#content').addClass('grid_16');
				<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_15" ): ?>
					jQuery('div#content').addClass('grid_15');
				<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_14" ): ?>
					jQuery('div#content').addClass('grid_14');
				<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_12" ): ?>
					jQuery('div#content').addClass('grid_12');
				<?php endif; ?>
		});
		</script>		
		<?php }} 
	// Hook into action
	add_action('open_body','infinity_grid_fallback');
	
	/**
	 * Add Footer Widget Class
	 *
	 * @package Infinity
	 * @subpackage base
	 */
	function infinity_footer_widget_class() { { ?>
	grid_8
	<?php }} 
	// Hook into action
	add_action('footer_widget_class','infinity_footer_widget_class');
}

/**
* Inject CSS to move sidebar to Left based on theme option
*
* TO DO: load this CSS in dynamic.css instead of injecting it inline
*
* @package Infinity
* @subpackage base
*/
function infinity_sidebar_position_css( ) 
{ ?>
<?php if ( infinity_option_get( 'infinity-core-options-sidebar_position' ) == "left" ): ?>
	<style type='text/css'>
		#content{float:right;}#inner-sidebar{margin-left:-23px;margin-right:10px;padding-right:25px;}
	</style> 
<?php endif; ?>
<?php
}
add_action( 'wp_head', 'infinity_sidebar_position_css' );
?>