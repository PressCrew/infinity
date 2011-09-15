<?php
/**
 * PIE API: option extensions, input group template file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */
?>
<div class="pie-easy-options-input-group" id="<?php $this->render_field_id() ?>">
	<?php
		// loop all field options
		foreach ( $this->component()->field_options as $value => $display ) {

			// keep track of current loop for element id
			$loop = ($loop) ? $loop + 1 : 1;

			// generate a unique element id
			$element_id = sprintf( 'pie-easy-option-input-group-%s-%d', $this->component()->name, $loop );

			// finally render the input element
			$this->render_input( $this->component()->input_type, $value, $element_id );

			// render the label ?>
			<label for="<?php print esc_attr( $element_id ) ?>"><?php print esc_html( $display ) ?></label><?php
		}
	?>
</div>