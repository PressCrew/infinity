<?php
/**
 * Infinity Theme: archive introduction box template
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

	// show post archives ?>
	<div class="category-box">
		<div id="category-description">
			<header>
				<h1 id= "category-title" class="page-title">
					<?php
						if ( is_day() ):
							printf( __( 'Daily Archives: %s', 'infinity-engine' ), '<span>' . get_the_date() . '</span>' );
						elseif ( is_month() ):
							printf( __( 'Monthly Archives: %s', 'infinity-engine' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );
						elseif ( is_year() ):
							printf( __( 'Yearly Archives: %s', 'infinity-engine' ), '<span>' . get_the_date( 'Y' ) . '</span>' );
						endif;
					?>
				</h1>
			</header>
		</div>
	</div>
	<?php

endif;