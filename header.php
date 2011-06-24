<?php
/**
 * Infinity Theme: header template
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<?php
	do_action( 'before_head' );
?>
<head profile="http://gmpg.org/xfn/11">
	<!-- basic title -->
		<?php if (function_exists('bp_page_title') && !bp_is_blog_page()) { ?>
 <title><?php bp_page_title() ?></title>
<?php } else { ?>
 <title><?php infinity_base_title(); ?></title>
<?php } ?>

	<!-- core meta tags -->
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	<!-- core link tags -->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> <?php _e( 'Blog Posts RSS Feed', 'buddypress' ) ?>" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> <?php _e( 'Blog Posts Atom Feed', 'buddypress' ) ?>" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php if ( is_singular() ) wp_enqueue_script( "comment-reply" ); ?>
	<?php
		wp_head();
	?>
</head>

<body <?php body_class() ?> id="infinity-base">
<?php
	do_action( 'open_body' );
?>
<div id="pattern" style=""></div>
<div id="wrapper">
	<?php
		do_action( 'open_wrapper' );
	?>
	<div class="container">
		<?php
			do_action( 'open_container' );
		?>
		<div class="top-wrap">
			<?php
				// show over menu?
				if ( has_nav_menu( 'over-menu'  ) ):
					infinity_get_template_part( 'top-menu', 'header' );
				endif;
			?>
			<!-- header -->
			<div id="header">
				<?php
					do_action( 'open_header' );
				?>
				
				
				<?php  if ( infinity_option( 'infinity_header_logo' ) ): ?>
				<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
				<<?php echo $heading_tag; ?> id="site-logo">
					<a href="<?php echo home_url( '/'  ); ?>" title="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>">
						<img src="<?php echo infinity_option_image_url( 'infinity_header_logo','full' ); ?>">				
					</a>
				</<?php echo $heading_tag; ?>>
				
				<? else : ?>
				<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
				<<?php echo $heading_tag; ?> id="site-title">
					<a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>"><?php bloginfo('name'); ?></a>
					<span id="site-description"><?php bloginfo('description'); ?></span>
				</<?php echo $heading_tag; ?>>
				<?php endif; ?>
				<?php
					// show primary menu?
					if ( has_nav_menu( 'primary-menu'  ) ):
						infinity_get_template_part( 'main-menu', 'header' );
					endif;

					do_action( 'close_header' );
				?>
			</div><!-- end header -->
			<?php
				// show the sub-menu?
				if ( has_nav_menu( 'sub-menu'  ) ):
					infinity_get_template_part( 'sub-menu', 'header' );
				endif;
				
				// the sub-header
				infinity_get_template_part( 'sub-header', 'header' );
			?>
		</div><!-- end top wrap -->

	<!-- start main wrap -->
	<div class="main-wrap">
	<?php
		do_action( 'open_main_wrap' );
	?>