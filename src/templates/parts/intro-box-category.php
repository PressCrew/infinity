<?php
/**
 * Infinity Theme: category introduction box template
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage templates
 * @since 1.2
 */

// are introduction boxes supported?
if ( infinity_base_show_intro_box() ):

	// show category archives ?>
	<div class="category-box">
		<div id="category-description">
			<header>
				<h1 id= "category-title" class="page-title">
					<?php
						printf( __( 'Category Archives: <span>%s</span>', 'infinity-engine' ), single_cat_title( '', false ) );
					?>
				</h1>
			</header>
			<?php
				// get cat desc
				$category_description = category_description();
				// print it?
				if ( !empty( $category_description ) ) {
					print $category_description;
				}
			?>
		</div>
	</div>
	<?php

endif;