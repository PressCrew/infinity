<?php
/**
 * Infinity Theme: Dashboard options screen, menu options template
 *
 * Variables:
 *		$options	Array of ICE_Option objects that need to be rendered
 *
 * @package Infinity
 * @subpackage dashboard-templates
 */
?>
<ul>
<?php foreach( $options as $option ): ?>
	<li><a id="section___<?php print esc_attr( $option->section ) ?>___option___<?php print esc_attr( $option->name ) ?>" class="infinity-cpanel-options-menu-show" href="#"><?php print esc_html( $option->title ) ?></a></li>
<?php endforeach; ?>
</ul>
