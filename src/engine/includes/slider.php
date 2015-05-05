<?php

// load the dynamic thumb plugin
require_once ICE_LIB_PATH . '/otf_regen_thumbs.php';

/**
 * Register custom post type for the slider.
 */
function infinity_slider_register_post_type()
{
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
		'rewrite'            => array( 'slug' => 'slide' ),
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => infinity_image_url( 'slider/icon.png' ),
		'supports'           => array( 'title', 'editor', 'thumbnail' )
	);

	register_post_type( 'infinity_slider', $args );
}

/**
 * Setup custom post type for the slider.
 */
function infinity_slider_setup_post_type()
{
	// is slider in custom post type mode?
	if ( infinity_slider_is_mode( 'custom' ) ) {
		// yep, register it
		infinity_slider_register_post_type();
	}
}
add_action( 'after_setup_theme', 'infinity_slider_setup_post_type', 102 );

/**
 * Load metaboxes class callback: https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */
function infinity_slider_init_metaboxes()
{
	if ( !class_exists( 'cmb_Meta_Box' ) ) {
		require_once( INFINITY_INC_PATH . '/metaboxes/init.php' );
	}
}
add_action( 'init', 'infinity_slider_init_metaboxes', 9999 );

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function infinity_slider_register_metaboxes( $meta_boxes = array() ) {

	// determine when to show metaboxes
	switch( infinity_slider_get_mode() ) {	
		// show only on 'infinity_slider' post type
		case 1:
			$slider_type = 'infinity_slider';
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
add_filter( 'manage_infinity_slider_posts_columns', 'infinity_slider_custom_column_add' );

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
add_action( 'manage_infinity_slider_posts_custom_column', 'infinity_slider_custom_column_content', 10, 2 );

/**
 * Renames the 'Featured Image' metabox for the slider's custom post type.
 *
 * To rename the metabox, we actually have to remove the 'Featured Image'
 * metabox and replace it with a custom one with the same functionality.
 */
function infinity_slider_rename_image_metabox()
{
	// remove default meta box
	remove_meta_box( 'postimagediv', 'infinity_slider', 'side' );
	
	// replace with our custom one
	add_meta_box(
		'postimagediv',
		__( 'Slide Image', 'infinity' ),
		'post_thumbnail_meta_box',
		'infinity_slider',
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
	if ( infinity_slider_is_mode( 'category' ) ) {
		// switch on hook suffix
		switch ( $GLOBALS['hook_suffix'] ) {
			// post edit screens
			case 'post-new.php' :
			case 'post.php' :
				// get category id set in slider options
				$cat_id = infinity_slider_get_category_id();
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
function infinity_slider_get_mode( $force = false )
{
	// mode is null by default
	static $mode = null;

	// is mode null?
	if ( null === $mode || true === $force ) {
		// yep, get the setting
		$mode = (integer) infinity_option_get( 'slider.mode' );
	}

	// return mode
	return $mode;
}

/**
 * Return slider width setting.
 *
 * @return string
 */
function infinity_slider_get_width()
{
	return infinity_option_get( 'slider.width' );
}

/**
 * Return slider height setting.
 *
 * @return string
 */
function infinity_slider_get_height()
{
	return infinity_option_get( 'slider.height' );
}

/**
 * Return slider category id setting.
 *
 * @return integer
 */
function infinity_slider_get_category_id()
{
	return (int) infinity_option_get( 'slider.category' );
}

/**
 * Return slider time setting.
 *
 * @return integer
 */
function infinity_slider_get_time()
{
	return (int) infinity_option_get( 'slider.time' );
}

/**
 * Return slider transition setting.
 *
 * @return integer
 */
function infinity_slider_get_transition()
{
	return (int) infinity_option_get( 'slider.transition' );
}

/**
 * Return slider amount setting.
 *
 * @return integer
 */
function infinity_slider_get_amount()
{
	return (int) infinity_option_get( 'slider.amount' );
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
		return ( infinity_slider_get_mode() >= 1 );
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

function infinity_slider_is_mode( $mode )
{
	switch( $mode ) {
		// custom mode check
		case 1:
		case 'custom':
			return ( infinity_slider_get_mode() === 1 );
		// category mode check
		case 2:
		case 'category':
			return ( infinity_slider_get_mode() === 2 );
	}

	// no mode match
	return false;
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

//
// Template tags
//

/**
 * Set up slider query and return true if loop should continue.
 *
 * @global WP_Query $infinity_slider_query
 * @return boolean
 */
function infinity_slider_have_slides()
{
	global $infinity_slider_query;

	// do we need to set up slider query?
	if ( true === empty( $infinity_slider_query ) ) {

		// yes, setup default slider query args
		$query_args = array(
			'order' => 'ASC',
			'posts_per_page' => '-1'
		);

		// get slider amount setting
		$posts_per_page = infinity_slider_get_amount();

		// get a custom amount?
		if ( false === empty( $posts_per_page ) ) {
			// yes, override default
			$query_args['posts_per_page'] = $posts_per_page;
		}

		// custom mode?
		if ( infinity_slider_is_mode( 'custom' ) ) {
			// yes, use our custom post type
			$query_args['post_type'] = 'infinity_slider';
		}

		// category mode?
		if ( infinity_slider_is_mode( 'category' ) ) {
			// yes, use configured category
			$query_args['cat'] = infinity_slider_get_category_id();
		}

		// new slider query
		$infinity_slider_query = new WP_Query( $query_args );
	}

	// did we get anything?
	return $infinity_slider_query->have_posts();
}

/**
 * Set up next slide for the loop.
 * 
 * @global WP_Query $infinity_slider_query
 */
function infinity_slider_the_slide()
{
	global $infinity_slider_query;

	// tell query object to setup the slide
	$infinity_slider_query->the_post();
}

/**
 * Returns true if current slide's caption should be shown.
 *
 * @return boolean
 */
function infinity_slider_the_slide_show_caption()
{
	// try to get hide caption setting for post
	$hide_caption = get_post_meta( get_the_ID(), INFINITY_META_KEY_PREFIX . 'slider_hide_caption', true );

	// return true unless hide caption is explicitly "yes"
	return ( 'yes' !== $hide_caption );
}

/**
 * Returns true if current slide has video enabled.
 *
 * @return boolean
 */
function infinity_slider_the_slide_show_video()
{
	// try to get video enable setting for post
	$video_enabled = get_post_meta( get_the_ID(), INFINITY_META_KEY_PREFIX . 'slider_video_enable', true );

	// return true if enable video is explicitly "yes"
	return ( 'yes' === $video_enabled );
}

/**
 * Returns true if current slide has a post thumbnail.
 * 
 * @return booleanS
 */
function infinity_slider_the_slide_has_thumbnail()
{
	return has_post_thumbnail();
}

/**
 * Print the post thumbnail for the current slide.
 */
function infinity_slider_the_slide_thumbnail()
{
	the_post_thumbnail( array( infinity_slider_get_width(), infinity_slider_get_height() ) );
}

/**
 * Return post thumbnail of the current slide.
 *
 * @param integer $slide_id The post id of the slide to retrieve thumbnail for.
 * @return string
 */
function infinity_slider_get_the_slide_thumbnail( $slide_id = null )
{
	return get_the_post_thumbnail( $slide_id, array( infinity_slider_get_width(), infinity_slider_get_height() ) );
}

/**
 * Print permalink of the current slide.
 */
function infinity_slider_the_slide_permalink()
{
	echo esc_url( infinity_slider_get_the_slide_permalink() );
}

/**
 * Return permalink for the current slide.
 *
 * @return string
 */
function infinity_slider_get_the_slide_permalink()
{
	// try to get custom URL from post meta
	$custom_url = get_post_meta( get_the_ID(),  INFINITY_META_KEY_PREFIX . 'slider_custom_url', true );

	// did we get a custom url?
	if ( false === empty( $custom_url ) ) {
		// yes, return it
		return $custom_url;
	} else {
		// no, return default permalink
		return get_the_permalink();
	}
}

/**
 * Print the title for the current slide.
 */
function infinity_slider_the_slide_title()
{
	the_title();
}

/**
 * Return the title of the current slide.
 *
 * @return string
 */
function infinity_slider_get_the_slide_title()
{
	return get_the_title();
}

/**
 * Print the excerpt for the current slide.
 */
function infinity_slider_the_slide_excerpt()
{
	echo infinity_slider_get_the_slide_excerpt();
}

/**
 * Return the excerpt for the current slide.
 *
 * @return string
 */
function infinity_slider_get_the_slide_excerpt()
{
	// try to get custom excerpt from post meta
	$custom_excerpt = get_post_meta( get_the_ID(), INFINITY_META_KEY_PREFIX . 'slider_excerpt', true );

	// did we get a custom excerpt string?
	if ( false === empty( $custom_excerpt ) ) {
		// yes, use it
		return wpautop( $custom_excerpt );
	} else {
		// no, use generate excerpt from
		return apply_filters( 'the_content', infinity_bp_create_excerpt( get_the_content() ) );
	}
}

/**
 * Print the video url for the current slide.
 */
function infinity_slider_the_slide_video_content()
{
	echo apply_filters( 'the_content', infinity_slider_get_the_slide_video_url() );
}

/**
 * Print the video url for the current slide.
 */
function infinity_slider_the_slide_video_url()
{
	echo infinity_slider_get_the_slide_video_url();
}

/**
 * Return the video url for the current slide.
 *
 * @return string
 */
function infinity_slider_get_the_slide_video_url()
{
	// try to get video url from post meta
	$video_url = get_post_meta( get_the_ID(), INFINITY_META_KEY_PREFIX . 'slider_video_url', true );

	// did we get a video url?
	if ( false === empty( $video_url ) ) {
		// yes, use it
		return $video_url;
	} else {
		// no, this is bad
		return false;
	}
}

/**
 * Print slider width setting.
 *
 * @param boolean $escape Set to true to make value attribute safe.
 */
function infinity_slider_width( $escape = true )
{
	if ( true === $escape ) {
		echo esc_attr( infinity_slider_get_width() );
	} else {
		echo infinity_slider_get_width();
	}
}

/**
 * Print slider height setting.
 *
 * @param boolean $escape Set to true to make value attribute safe.
 */
function infinity_slider_height( $escape = true )
{
	if ( true === $escape ) {
		echo esc_attr( infinity_slider_get_height() );
	} else {
		echo infinity_slider_get_height();
	}
}

/**
 * Print url of no slides image.
 *
 * @param boolean $escape Set to true to make value attribute safe.
 */
function infinity_slider_no_slides_image_url( $escape = true )
{
	if ( true === $escape ) {
		echo esc_attr( infinity_slider_get_no_slides_image_url() );
	} else {
		echo infinity_slider_get_no_slides_image_url();
	}
}

/**
 * Return url of no slides image.
 *
 * @staticvar string $url Cached image url.
 * @return string
 */
function infinity_slider_get_no_slides_image_url()
{
	// cache url for performance
	static $url = null;

	// is url null?
	if ( null === $url ) {
		// find the image url
		$url  = infinity_image_url( 'slider/bg.png' );
	}

	// return it
	return $url;
}

/**
 * Print title text for no slides found.
 */
function infinity_slider_no_slides_title()
{
	_e( 'No slides have been added yet!', 'infinity' );
}

/**
 * Print helpful text for no slides found.
 */
function infinity_slider_no_slides_help()
{
	// start helpful text
	_e( 'Did you know you can easily add slides to your homepage?', 'infinity' );

	// add a space
	echo ' ';

	// more helpful text depending on mode
	if ( infinity_slider_is_mode( 'custom' ) ) {
		// need to add custom slides
		_e( 'Simply go to the admin dashboard and add a new <strong>Custom Slide</strong>.', 'infinity' );
	} elseif ( infinity_slider_is_mode( 'category' ) ) {
		// get category object
		$category = get_category( infinity_slider_get_category_id() );
		// need to add posts to configured category
		printf(
			__( 'Simply go to the admin dashboard and add a new post to the <strong>%s</strong> category.', 'infinity' ),
			$category->name
		);
	}
}

/**
 * Print helpful text for slide missing content.
 */
function infinity_slider_no_content_help()
{
	// start helpful text
	_e( 'This slide has no content!', 'infinity' );

	// add a space
	echo ' ';

	// more helpful text depending on mode
	if ( infinity_slider_is_mode( 'custom' ) ) {
		// need to edit this custom slide
		_e( 'Please go to the admin dashboard and edit this <strong>Custom Slide</strong>.', 'infinity' );
	} elseif ( infinity_slider_is_mode( 'category' ) ) {
		// need to edit the post
		_e( 'Please go to the admin dashboard and edit this post.', 'infinity' );
	}
}

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
		$time = infinity_slider_get_time();

		// did we get a time?
		if ( false === empty( $time ) ) {
			// yep, override it
			$options[ 'pause' ] = $time;
		}

		// get transition option
		$trans = infinity_slider_get_transition();

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

//
// Compat functions
//

/**
 * Rename all deprecated post types known to exist for older theme versions.
 *
 * @global wpdb $wpdb
 */
function infinity_slider_compat_post_types()
{
	global $wpdb;

	// make sure current post type is registered
	infinity_slider_register_post_type();

	// build up old => new types map
	$typemap = array(
		'features' => 'infinity_slider'
	);

	// loop the type map
	foreach( $typemap as $old_type => $new_type ) {

		// get all posts of old type
		$posts = get_posts( array(
			'post_status' => array( 'any', 'trash', 'auto-draft' ),
			'post_type' => $old_type
		));

		// loop all posts
		foreach( $posts as $post ) {
			// set the new post type
			set_post_type( $post->ID, $new_type );
			// fix guid field
			$wpdb->update(
				$wpdb->posts,
				array( 'guid' => get_permalink( $post->ID ) ),
				array( 'ID' => $post->ID )
			);
		}
	}

	// flush rewrite rules just in case
	flush_rewrite_rules();
}
add_action( 'infinity_dashboard_activated', 'infinity_slider_compat_post_types' );