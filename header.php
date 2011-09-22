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
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<?php
	infinity_get_template_part( 'templates/parts/header-head');	
?>
<body <?php body_class() ?> id="infinity-base">
<?php
	do_action( 'open_body' );
?>

<div id="wrapper" class="hfeed">
	<?php
		do_action( 'open_wrapper' );
	?>

	<?php // the header-banner template contains all the markup for the header(logo) and menus. You can easily fork/modify this in your child theme without having to overwrite the entire header.php file.
		infinity_get_template_part( 'templates/parts/header-banner');
	?>
	<?php
			do_action( 'open_container' );
	?>
			
	<!-- start main wrap. the main-wrap div will be closed in the footer template -->
	<div class="main-wrap">
	<?php
		do_action( 'open_main_wrap' );
	?>