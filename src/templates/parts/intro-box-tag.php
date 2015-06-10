<?php
/**
 * Infinity Theme: tag introduction box template
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

	// show tag archives ?>
	<div class="tag-box">
		<div id="tag-description">
		<header>
			<h1 id="tag-title" class="page-title">
				<?php
					printf( __( 'Tag Archives: <span>%s</span>', 'infinity-engine' ), single_tag_title( '', false ) );
				?>
			</h1>
		</header>
		<?php
			// try to get tag description
			$tag_description = tag_description();
			// have a description?
			if ( !empty( $tag_description ) ):
				// print description
				echo  $tag_description ;
			endif;
		?>
		</div>
	</div>
	<?php

endif;