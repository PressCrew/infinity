<?php
/**
 * Infinity Theme: Dashboard options screen, option meta information template
 *
 * DO NOT call template tags to render elements in this template!
 *
 * This template is called from option the render. All rendering methods
 * are available from the $renderer variable (see below).
 *
 * Variables:
 *		$option		The current Pie_Easy_Options_Option object being rendered
 *		$renderer	The Pie_Easy_Options_Renderer object that is rendering the option
 *
 * @package infinity
 * @subpackage dashboard-templates
 */
?>
<div class="infinity-cpanel-options-last-modified">
	<?php _e('Last Modified:', infinity_text_domain) ?> <?php echo $renderer->render_date_updated() ?>
</div>
