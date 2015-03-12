<?php
/**
 * Template Name: Custom or Category Slider
 *
 * This template either displays Slides taken from the custom post type,
 * or loops through posts from a certain category. This is based on the
 * theme options set by the user.
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @since 1.0
 */
?>
<?php

// slider type
$slider_mode = infinity_slider_mode();

//slider sizes
$sliderheight = infinity_option_get( 'slider:height' );
$sliderwidth = infinity_option_get( 'slider:width' );

// locate no slides image url
$no_slides_url  = infinity_image_url( 'slides-bg.png' );
$no_slider_text = '';

// setup slider query args
$query_args = array();

$query_args['order'] = 'ASC';

$posts_per_page = infinity_option_get( 'slider:amount' );
if ( ! empty( $posts_per_page ) ) {
	$query_args['posts_per_page'] = (int) infinity_option_get( 'slider:amount' );
} else {
	$query_args['posts_per_page'] = '-1';
}

// site features
if ( $slider_mode == 1 ) {
	$query_args['post_type'] = 'infinity_slider';

	$no_slider_text = __( 'Did you know you can easily add introduction slides to your homepage? Simply visit your admin panel and add a new <strong>Custom Slide</strong>.', 'infinity' );
}

// post category
if ( $slider_mode == 2 ) {
	$cat_id = infinity_option_get( 'slider:category' );
	$cat    = get_category( $cat_id );

	$query_args['cat'] = $cat_id;

	$no_slider_text = sprintf( __( 'Did you know you can easily add introduction slides to your homepage? Simply visit your admin panel and add a new post in the <strong>%s</strong> category.', 'infinity' ), $cat->name );
}

// setup the slider query
$slider_query = new WP_Query( $query_args );

?>

<div class="flex-container">
	<div class="flexslider">
	  	<ul class="slides">


<?php
if( $slider_query->have_posts() ) :
	while( $slider_query->have_posts() ) :
		$slider_query->the_post();

		// slide URL
		$slide_url = get_post_meta( $post->ID,  INFINITY_META_KEY_PREFIX . 'slider_custom_url', true );
		if ( empty( $slide_url ) ) {
			$slide_url = get_permalink();
		} else {
			$slide_url = esc_url( $slide_url );
		}

		// caption
		$hide_caption = get_post_meta( $post->ID, INFINITY_META_KEY_PREFIX . 'slider_hide_caption', true );
		if ( ! $hide_caption ) { $hide_caption = "no"; }

		$slider_excerpt = wpautop( get_post_meta( get_the_ID(), INFINITY_META_KEY_PREFIX . 'slider_excerpt', true ) );
		if ( empty( $slider_excerpt ) ) {
			$slider_excerpt = apply_filters( 'the_content', cbox_create_excerpt( get_the_content() ) );
		}

		// video
		$video_value = get_post_meta( $post->ID, INFINITY_META_KEY_PREFIX . 'slider_video_enable', true);
		if ( ! $video_value ) { $video_value = "no"; }

?>

		<!-- Loop through slides  -->
	<?php if( has_post_thumbnail() && $video_value == "no" ) :?>
		<li>
			<!-- Image -->
			<a href="<?php echo $slide_url; ?>">
				<?php the_post_thumbnail( array( $sliderwidth, $sliderheight ) ) ?>
			</a>

			<!-- Caption -->
			<?php if ( $hide_caption == "no" ): /* Hide the caption if box is checked */ ?>
				<div class="flex-caption">
					<h3>
						<a href="<?php echo $slide_url; ?>">
							<?php the_title_attribute();?>
						</a>
					</h3>
					<?php echo $slider_excerpt; ?>
				</div>
			<?php endif;?>

		</li>
	<?php elseif ( $video_value == "yes" ): /* Display a video if one has been set */ ?>
		<li class="slide-video-embed">
			<?php echo apply_filters( 'the_content', get_post_meta( get_the_ID(), INFINITY_META_KEY_PREFIX . 'slider_video_url', true ) ); ?>
		</li>
	<?php /* Fallback to default slide if no custom slides are present */ else :?>

		<li>
			<img src="<?php echo $no_slides_url ?>" width="<?php echo esc_attr( $sliderwidth ) ?>" height="319" alt="" style="height:319px;" />
				<div class="flex-caption">
					<h3><?php _e( 'No slides added yet!', 'infinity' ); ?></h3>
					<p><?php echo $no_slider_text; ?></p>
				</div>
		</li>

	<?php endif;?>

	<?php endwhile;

else:
?>

		<!-- Fallback to default slide if no custom slides are present -->
		<li>
			<img src="<?php echo $no_slides_url ?>" width="<?php echo esc_attr( $sliderwidth ) ?>" height="319" alt="" style="height:319px;" />

			<div class="flex-caption">
				<h3><?php _e( 'No slides added yet!', 'infinity' ); ?></h3>
				<p><?php echo $no_slider_text; ?></p>
			</div>
		</li>

<?php endif; ?>

		</ul>
	</div>
</div>
