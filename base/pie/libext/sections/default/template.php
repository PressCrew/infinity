<?php
/**
 * PIE API: section extensions, default section template file
 *
 * Variables:
 *		$section	The current Pie_Easy_Sections_Section object being rendered
 *		$renderer	The Pie_Easy_Sections_Renderer object that is rendering the section
 *		$components	Array of components that make up the content of the section
 *
 * The $components array is provided in case you want to get more fancy that just
 * using the renderer's basic render_components() method.
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage sections
 * @since 1.0
 */
?>
<div class="<?php $renderer->render_classes( 'pie-easy-sections-block' ) ?>">
	<div class="ui-widget-header ui-state-active <?php $renderer->render_class_title( 'pie-easy-sections-title' ) ?>">
		<?php $renderer->render_title(); ?> <?php _e( 'Options', pie_easy_text_domain ); ?>
	</div>
	<div class="<?php $renderer->render_class_content( 'pie-easy-sections-content' ) ?>">
		<?php $section->render_components() ?>
	</div>
</div>
