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
	<li><a id="infinity_<?php echo esc_attr( $option->get_aname() ) ?>" data-ice-group="<?php echo esc_attr( $option->get_group() ) ?>" data-ice-name="<?php echo ( $option->get_name() ) ?>" data-ice-section="<?php echo esc_attr( $option->get_property('section') ) ?>" class="infinity-cpanel-options-menu-show" href="#"><?php print esc_html( $option->get_property( 'title' ) ) ?></a></li>
<?php endforeach; ?>
</ul>
