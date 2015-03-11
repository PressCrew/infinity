<?php

/**
 * Register custom post type for the slider.
 */
function infinity_slider_register_post_type()
{
	// only register post type if slider is in post type mode
	if ( infinity_slider_mode() === 1 ) {

		$labels = array(
			'name'               => _x( 'Custom Slides', 'post type general name', 'infinity' ),
			'singular_name'      => _x( 'Custom Slide', 'post type singular name', 'infinity' ),
			'all_items'          => __( 'All Slides', 'infinity' ),
			'add_new'            => __( 'Add Slide', 'infinity' ),
			'add_new_item'       => __( 'Add Slide', 'infinity' ),
			'edit_item'          => __( 'Edit Slide', 'infinity' ),
			'new_item'           => __( 'New Slide', 'infinity' ),
			'search_items'       => __( 'Search slides', 'infinity' ),
			'not_found'          => __( 'No slides found', 'infinity' ),
			'not_found_in_trash' => __( 'No slides found in trash', 'infinity' ),
			'parent_item_colon'  => ''
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'query_var'          => true,
			'rewrite'            => true,
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => infinity_image_url( 'slides-icon.png' ),
			'supports'           => array( 'title', 'editor', 'thumbnail' )
		);

		register_post_type( 'features', $args );
	}
}
add_action( 'after_setup_theme', 'infinity_slider_register_post_type', 101 );

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function infinity_slider_register_metaboxes( $meta_boxes = array() ) {

	// determine when to show metaboxes
	switch( infinity_slider_mode() ) {	
		// show only on 'features' post type
		case 1:
			$slider_type = 'features';
			break;
		// show them on all posts when a category is used for the slider
		case 2:
			$slider_type = 'post';
			break;
		// don't show metaboxes
		default:
			return;
	}

	$meta_boxes[] = array(
		'id'         => 'infinity_slider_general_options',
		'title'      => __( 'Slide Options', 'infinity' ),
		'pages'      => array( $slider_type ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => __( 'Slide Caption', 'infinity' ),
				'desc' => __( 'Write down the text you would like to display in the slider. You can leave this empty if you want to show an excerpt of the post you have written above.', 'infinity' ),
				'id'   => INFINITY_META_KEY_PREFIX . 'slider_excerpt',
				'type' => 'wysiwyg',
					'options' => array(
					    'media_buttons' => false, // show insert/upload button(s)
					),
			),
			array(
				'name'    => __( 'Hide Caption?', 'infinity' ),
				'desc'    => __( 'Do you want to completely hide the caption for this slide? This will only display your slide image', 'infinity' ),
				'id'      => INFINITY_META_KEY_PREFIX . 'slider_hide_caption',
				'type'    => 'radio_inline',
				'std' => 'no',
				'options' => array(
					array( 'name' => 'Yes', 'value' => 'yes', ),
					array( 'name' => 'No', 'value' => 'no', ),
				),
			),
			array(
				'name' => __( 'Custom URL', 'infinity' ),
				'desc' => __( 'The full URL you would like the slide to point to. Example: http://www.google.com.  Leave this blank to use the regular slider post permalink.', 'infinity' ),
				'id'   => INFINITY_META_KEY_PREFIX . 'slider_custom_url',
				'type' => 'text',
			),
		),
	);

	// Add other metaboxes as needed
	$meta_boxes[] = array(
			'id'         => 'infinity_slider_video_options',
			'title'      => __( 'Video Options', 'infinity' ),
			'pages'      => array( $slider_type ), // Post type
			'context'    => 'normal',
			'priority'   => 'high',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name'    => __( 'Embed a Video?', 'infinity' ),
					'desc'    => __( 'Do you want to display a video inside your slide? Note: The video will replace your caption text and slide image.', 'infinity' ),
					'id'      => INFINITY_META_KEY_PREFIX . 'slider_video_enable',
					'type'    => 'radio_inline',
					'std' => 'no',
					'options' => array(
						array( 'name' => 'Yes', 'value' => 'yes', ),
						array( 'name' => 'No', 'value' => 'no', ),
					),
				),
				array(
					'name' => __( 'Video URL', 'infinity' ),
					'desc' => __( 'Enter a Youtube or Vimeo URL. example: http://www.youtube.com/watch?v=iMuFYnvSsZg', 'infinity' ),
					'id'   => INFINITY_META_KEY_PREFIX . 'slider_video_url',
					'type' => 'oembed',
				),
			)
	);

	return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'infinity_slider_register_metaboxes' );

/**
 * Get a slide's image url.
 *
 * @param integer $post_id The post id.
 * @param string $size The size of the thumbnail to get.
 * @return string The thumbnail url.
 */
function infinity_slider_get_image_url( $post_id, $size='thumbnail' )
{
	// get post thumbnail id
	$thumb_id = get_post_thumbnail_id($post_id);

	// get one?
	if ( true === is_numeric( $thumb_id ) ) {
		// yes, get image src array
		$thumb_img = wp_get_attachment_image_src( (int) $thumb_id, $size );
		// get something?
		if ( true === isset( $thumb_img[0] ) ) {
			// yes, return it
			return $thumb_img[0];
		}
	}

	// not good
	return '';
}

/**
 * Add new column to the Custom Slides index.
 *
 * @param array $columns Array of columns passed by filter.
 * @return array
 */
function infinity_slider_custom_column_add( $columns )
{
	// add our column to the array
	$columns[ 'infinity_slider_image' ] = __( 'Slide Image', 'infinity' );
	
	// return it
	return $columns;
}
add_filter( 'manage_features_posts_columns', 'infinity_slider_custom_column_add' );

/**
 * Show the slide image in the new column.
 *
 * @param string $column_name The name of the column being rendered.
 * @param integer $post_id The post id of the column being rendered.
 */
function infinity_slider_custom_column_content( $column_name, $post_id )
{
	// is this our column?
	if ( $column_name === 'infinity_slider_image' ) {
		// yes, try to get the thumb url
		$thumb_url = infinity_slider_get_image_url( $post_id );
		// did we get one?
		if ( false === empty( $thumb_url ) ) {
			// yes, render it
			?><img src="<?php echo $thumb_url ?>" /><?php
		}
	}
}
add_action( 'manage_features_posts_custom_column', 'infinity_slider_custom_column_content', 10, 2 );

/**
 * Renames the 'Featured Image' metabox for the slider's custom post type.
 *
 * To rename the metabox, we actually have to remove the 'Featured Image'
 * metabox and replace it with a custom one with the same functionality.
 */
function infinity_slider_rename_image_metabox()
{
	// remove default meta box
	remove_meta_box( 'postimagediv', 'features', 'side' );
	
	// replace with our custom one
	add_meta_box(
		'postimagediv',
		__( 'Slide Image' ),
		'post_thumbnail_meta_box',
		'features',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'infinity_slider_rename_image_metabox' );

/**
 * Add inline JS to toggle "Slide Options" metabox on a "Post" admin page.
 *
 * When the slider's featured category is checked in the "Post" admin page,
 * this javascript will either show or hide the "Slide Options" and "Video
 * Options" metaboxes.
 *
 * This is only used if the slider is set to use a post category.
 */
function infinity_slider_admin_footer_script()
{
	// is slider mode set to category?
	if ( 2 === infinity_slider_mode() ) {
		// switch on hook suffix
		switch ( $GLOBALS['hook_suffix'] ) {
			// post edit screens
			case 'post-new.php' :
			case 'post.php' :
				// get category id set in slider options
				$cat_id = infinity_option_get( 'slider:category' );
				break;
			// every other screen
			default:
				// bail
				return;
		}
	} else {
		// other slider mode, bail
		return;
	}

	// render the embedded footer script ?>
	<script type="text/javascript">
	//<![CDATA[
		jQuery(function($) {
			function infinity_slider_toggle_metaboxes() {
				$('#infinity_slider_general_options, #infinity_slider_video_options').hide();

				$('#categorychecklist input[type="checkbox"]').each(function(i,e){
					var id = $(this).attr('id').match(/-([0-9]*)$/i);
					id = (id && id[1]) ? parseInt(id[1]) : null ;

					if ($.inArray(id, [<?php echo $cat_id; ?>]) > -1 && $(this).is(':checked')) {
						$('#infinity_slider_general_options, #infinity_slider_video_options').show();
					}
				});
			}

			$('#taxonomy-category').on( 'click', '#categorychecklist input[type="checkbox"]', infinity_slider_toggle_metaboxes );

			infinity_slider_toggle_metaboxes();
		});
	//]]>
	</script><?php
}
add_action( 'admin_footer', 'infinity_slider_admin_footer_script' );

/**
 * Return the value of the slider mode.
 *
 * @staticvar boolean $mode Cached value of mode setting.
 * @param boolean $force Set to true to bypass cached value of mode and get live option setting.
 * @return integer
 */
function infinity_slider_mode( $force = false )
{
	// mode is null by default
	static $mode = null;

	// is mode null?
	if ( null === $mode || true === $force ) {
		// yep, get the setting
		$mode = (integer) infinity_option_get( 'slider:mode' );
	}

	// return mode
	return $mode;
}

/**
 * Returns true if slider theme support is enabled and slider mode is set to a display option.
 *
 * @return boolean
 */
function infinity_slider_is_enabled()
{
	// is slider support on?
	if ( true === current_theme_supports( 'infinity:slider' ) ) {
		// check slider mode value
		return ( infinity_slider_mode() >= 1 );
	}

	// slider not enabled
	return false;
}

/**
 * Returns true if slider is enabled and the current page is using the slider template.
 *
 * @return boolean
 */
function infinity_slider_is_on_page()
{
	return (
		true === infinity_slider_is_enabled() &&
		true === is_page_template( 'homepage-template.php' )
	);
}

/**
 * Register slider assets
 */
function infinity_slider_register_assets()
{
	// bxslider styles
	ice_register_style(
		'bxslider',
		array(
			'src' => INFINITY_THEME_URL . '/assets/css/bxslider/jquery.bxslider.css',
			'condition' => 'infinity_slider_is_on_page'
		)
	);

	// bxslider script
	ice_register_script(
		'bxslider',
		array(
			'src' => INFINITY_THEME_URL . '/assets/js/jquery.bxslider.min.js',
			'in_footer' => true,
			'condition' => 'infinity_slider_is_on_page'
		)
	);
}
add_action( 'after_setup_theme', 'infinity_slider_register_assets', 11 );

/**
 * Action callback for localizing bxslider script in the footer.
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_slider_localize_script()
{
	// is slider enabled?
	if ( true === infinity_slider_is_enabled() ) {

		// options to convert to JS object
		$options = array(
			'adaptiveHeight' => true,
			'auto' => true,
			'autoHover' => true,
			'mode' => 'fade',
			'video' => true,
			'useCSS' => false,
			'controls' => false,
			'pause' =>  5000,
			'speed' => 600
		);

		// get time option
		$time = infinity_option_get( 'slider:time' );

		// did we get a time?
		if ( false === empty( $time ) ) {
			// yep, override it
			$options[ 'pause' ] = $time;
		}

		// get transition option
		$trans = infinity_option_get( 'slider:transition' );

		// did we get a transition?
		if ( false === empty( $trans ) ) {
			// yep, override it
			$options[ 'speed' ] = $trans;
		}

		// pass through filter
		$options_final = apply_filters( 'infinity_slider_localize_script_options', $options );

		// new script object
		$script = new ICE_Script();

		// create logic helper and add our variable
		$script
			->logic( 'vars' )
			->add_variable( 'infinity_slider_options', (object) $options_final );
		
		// spit it out
		$script->render( true );
	}
}
add_action( 'wp_print_footer_scripts', 'infinity_slider_localize_script', 9 );

/**
 * Rename all deprecated slider postmeta keys known to exist for older theme versions.
 *
 * @global wpdb $wpdb
 */
function infinity_slider_compat_postmeta_keys()
{
	global $wpdb;

	// build up old => new key names map
	$keymap = array(
		'_cbox_custom_url' => INFINITY_META_KEY_PREFIX . 'slider_custom_url',
		'_cbox_hide_caption' => INFINITY_META_KEY_PREFIX . 'slider_hide_caption',
		'_cbox_slider_excerpt' => INFINITY_META_KEY_PREFIX . 'slider_excerpt',
		'_cbox_enable_custom_video' => INFINITY_META_KEY_PREFIX . 'slider_video_enable',
		'_cbox_video_url' => INFINITY_META_KEY_PREFIX . 'slider_video_url'
	);

	// loop the key map
	foreach( $keymap as $old_key => $new_key ) {
		// update every row matching old key with new key
		$wpdb->update(
			$wpdb->postmeta,
			array( 'meta_key' => $new_key ),
			array( 'meta_key' => $old_key )
		);
	}
}
add_action( 'infinity_dashboard_activated', 'infinity_slider_compat_postmeta_keys' );