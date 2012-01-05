<?php
/**
 * Add Core Options
 *
 * @package Infinity
 * @subpackage base
 * @todo move this to a feature extension, no direct plugin support
 */


// ! // Google Analytics
function infinity_google_analytics() { { ?>
	<!-- html -->
		<?php echo infinity_option_get( 'infinity-core-options-google_analytics' ); ?>
	<!-- end -->
	<?php }} 
// Hook into action
add_action('close_body','infinity_google_analytics');

// ========================== 
// ! // Custom Favicon
// ========================== 
function infinity_custom_favicon() { { ?>
	<?php if ( infinity_option_get( 'infinity-core-options-custom_favicon' ) ): ?>
		<link rel="shortcut icon" type="image/png" href="<?php echo infinity_option_image_url( 'infinity-core-options-custom_favicon', full ); ?>" />
	<?php else: ?>
		<link rel="shortcut icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.png" />
	<?php endif; ?>
	<?php }} 
// Hook into action
add_action('wp_head','infinity_custom_favicon'); 

// ========================== 
// ! // Apply Content Class based on Theme Options
// ========================== 
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

//do the same for the sidebar
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
 
// ! // Use a jQuery powered fallback for 3rd Party BuddyPress Plugins.
function infinity_custom_sidebar() { { ?>
	<script>
	jQuery(document).ready(function() {
			<?php if ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_17" ): ?>
				jQuery('#content').addClass('grid_17');
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_20" ): ?>
				jQuery('#content').addClass('grid_20');
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_19" ): ?>
				jQuery('#content').addClass('grid_19');
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_18" ): ?>
				jQuery('#content').addClass('grid_18');
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_16" ): ?>
				jQuery('#content').addClass('grid_16');
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_15" ): ?>
				jQuery('#content').addClass('grid_15');
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_14" ): ?>
				jQuery('#content').addClass('grid_14');
			<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_12" ): ?>
				jQuery('#content').addClass('grid_12');
			<?php endif; ?>
	});
	</script>		
	<?php }} 
// Hook into action
add_action('open_body','infinity_custom_sidebar');

// Custom CSS
add_action( 'wp_head', 'infinity_custom_css' );
function infinity_custom_css( ) {
    # get theme options
?>
<style type='text/css'>
<?php if ( infinity_option_get( 'infinity-core-options-sidebar_position' ) == "left" ): ?>
	#content{float:right;}#inner-sidebar{margin-left:-25px;margin-right:10px;padding-right:25px;}
<?php endif; ?>
</style> 
<?php
}
?>