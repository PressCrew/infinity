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
	<li><a id="section___<?php print esc_attr( $option->get_property( 'section' ) ) ?>___option___<?php print esc_attr( $option->get_property( 'name' ) ) ?>" class="infinity-cpanel-options-menu-show" href="#"><?php print esc_html( $option->get_property( 'title' ) ) ?></a></li>
<?php endforeach; ?>
</ul>
