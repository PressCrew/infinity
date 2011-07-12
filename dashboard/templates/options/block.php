<?php
/**
 * Infinity Theme: Dashboard options screen, option block (wrapper) template
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

// opening elements
$renderer->render_begin( 'infinity-cpanel-options-single' );

// start rendering option elements ?>
<div class="infinity-cpanel-options-single-header">
	<?php
		$renderer->render_label();
		$renderer->render_buttons();
	?>
</div>
<ul>
	<li><a href="#<?php $renderer->render_name() ?>-tabs-1"><?php _e('Edit Setting', infinity_text_domain) ?></a></li>
	<?php if ( $renderer->has_documentation() ): ?>
	<li><a href="#<?php $renderer->render_name() ?>-tabs-2"><?php _e('Documentation', infinity_text_domain) ?></a></li>
	<?php endif; ?>
	<?php if ( is_admin() ): ?>
	<li><a href="#<?php $renderer->render_name() ?>-tabs-3"><?php _e('Sample Code', infinity_text_domain) ?></a></li>
	<?php endif; ?>
</ul>
<div id="<?php $renderer->render_name() ?>-tabs-1">
	<p><?php $renderer->render_description() ?></p>
	<?php
		$renderer->render_field();
		$renderer->render_meta();
	?>
</div>
<?php if ( $renderer->has_documentation() ): ?>
<div id="<?php $renderer->render_name() ?>-tabs-2">
	<div class="infinity-docs"><?php $renderer->render_documentation( Pie_Easy_Scheme::instance()->theme_documentation_dirs() ) ?></div>
</div>
<?php endif; ?>
<?php if ( is_admin() ): ?>
<div id="<?php $renderer->render_name() ?>-tabs-3">
	<p><?php $renderer->render_sample_code() ?></p>
</div>
<?php endif;

// all done
$renderer->render_end();

?>