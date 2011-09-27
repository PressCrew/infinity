<?php 
/**
 * Infinity Theme: Header Content
 *
 * This template contains the Header Content. Fork this in your Child THeme
 * if you want to change the markup but don't want to mess around doctypes/meta etc!
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
<div class="top-wrap">
	<?php
		// show over menu?
		if ( has_nav_menu( 'over-menu'  ) ):
			infinity_get_template_part( 'templates/parts/top-menu', 'header' );
		endif;
	?>
	<!-- header -->
	<div id="header" role="banner">
		<?php
			do_action( 'open_header' );
			if ( !infinity_feature( 'infinity-header-logo' ) ):
		?>
		<?php
			$heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div';
		?>
		<<?php echo $heading_tag; ?> id="site-title">
			<a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>"><?php bloginfo('name'); ?></a>
			<span id="site-description"><?php bloginfo('description'); ?></span>
		</<?php echo $heading_tag; ?>>
		<?php
			endif;
			// show primary menu?
			if ( has_nav_menu( 'main-menu'  ) ):
				infinity_get_template_part( 'templates/parts/main-menu', 'header' );
			endif;

			do_action( 'close_header' );
		?>
	</div><!-- end header -->
	<?php
		// show the sub-menu?
		if ( has_nav_menu( 'sub-menu'  ) ):
			infinity_get_template_part( 'templates/parts/sub-menu', 'header' );
		endif;
	?>
</div><!-- end top wrap -->
			<div style="clear: both;"></div>
