<?php
/**
 * Infinity Theme: introduction boxes template
 *
 * @author Bowe Frankema <bowromir@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @subpackage templates
 * @since 1.0
 */

	// show the category box when on a category page
	if ( is_category() ):
?>
		<div class="category_box">
			<h1 id= "category-title" class="page-title">
				<?php
					printf( __( 'Category Archives: <span>%s</span>', infinity_text_domain ), single_cat_title( '', false ) );
				?>
			</h1>
			<?php
				category_description();
			?>
		</div>
<?php
	endif;
?>

<!-- Show The Categorybox when on a Category Page -->
<?php
	if ( is_tag() ):
?>
		<div class="tag_box">
			<h1 id="tag-title" class="page-title">
				<?php
					printf( __( 'Tag Archives: <span>%s</span>', infinity_text_domain ), single_tag_title( '', false ) );
				?>
			</h1>
			<?php
				tag_description();
			?>
		</div>
<?php
	endif;

	// load author box if on author page
	infinity_base_author_box();
?>