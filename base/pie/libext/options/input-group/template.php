<?php
/**
 * PIE API: option extensions, input group template file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */
?>
<div id="<?php $this->render_field_id() ?>" class="<?php $this->render_class( 'field' ) ?>">
	<?php
		// loop all field options
		foreach ( $this->component()->field_options as $value => $display ) {

			// keep track of current loop for element id
			$loop = ($loop) ? $loop + 1 : 1;

			// finally render the input element
			$this->render_input( $this->component()->input_type, $value, $this->component()->get_element_id( $loop ) );

			// render the label ?>
			<label for="<?php print esc_attr( $this->component()->get_element_id( $loop ) ) ?>"><?php print esc_html( $display ) ?></label><?php
		}
	?>
</div>