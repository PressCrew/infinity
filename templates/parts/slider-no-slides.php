<?php
/**
 * Infinity Theme: Slider, no slides found.
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2015 Bowe Frankema, Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage templates
 */
 ?>
<li>
	<img
		src="<?php infinity_slider_no_slides_image_url() ?>"
		width="<?php infinity_slider_width() ?>"
		height="<?php infinity_slider_height() ?>"
		style="width:<?php infinity_slider_width() ?>px; height:<?php infinity_slider_height() ?>px;"
	>
	<div class="flex-caption">
		<h3><?php infinity_slider_no_slides_title() ?></h3>
		<p>
			<?php infinity_slider_no_slides_help() ?>
		</p>
	</div>
</li>