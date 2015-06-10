<?php
/**
 * ICE API: section extensions, cpanel section single option template file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2014 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage extensions
 * @since 1.2
 */

/* @var $option ICE_Option */
/* @var $renderer ICE_Option_Renderer */

// start rendering in bypass mode
$renderer = $option->render_bypass();

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
	<li><a href="#<?php $renderer->render_name() ?>-tabs-1"><?php _e( 'Edit Setting', 'infinity-engine') ?></a></li>
	<?php if ( $renderer->has_documentation() ): ?>
	<li><a href="#<?php $renderer->render_name() ?>-tabs-2"><?php _e( 'Documentation', 'infinity-engine') ?></a></li>
	<?php endif; ?>
	<?php if ( true == INFINITY_DEV_MODE ): ?>
		<li><a href="#<?php $renderer->render_name() ?>-tabs-3"><?php _e( 'Sample Code', 'infinity-engine') ?></a></li>
	<?php endif; ?>
</ul>
<div id="<?php $renderer->render_name() ?>-tabs-1">
	<p><?php $renderer->render_description() ?></p>
	<?php
		$renderer->load_template();
	?>
</div>
<?php if ( $renderer->has_documentation() ): ?>
<div id="<?php $renderer->render_name() ?>-tabs-2">
	<div class="infinity-docs"><?php $renderer->render_documentation() ?></div>
</div>
<?php endif; ?>
<?php if ( true == INFINITY_DEV_MODE ): ?>
<div id="<?php $renderer->render_name() ?>-tabs-3">
	<p><?php include 'template_sample_code.php'; ?></p>
</div>
<?php endif;

// all done
$renderer->render_end();

// clean up
unset( $renderer );