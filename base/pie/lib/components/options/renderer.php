<?php
/**
 * PIE API: options renderer class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-components
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'utils/docs' );

/**
 * Make rendering options easy
 *
 * @package PIE-components
 * @subpackage options
 */
abstract class Pie_Easy_Options_Renderer extends Pie_Easy_Renderer
{
	/**
	 * The field which contains all of the options which were rendered
	 */
	const FIELD_MANIFEST = '__opt_manifest__';

	/**
	 * Renders option container opening and flash message elements
	 */
	public function render_begin( $block_class = null )
	{
		// begin rendering ?>
		<div class="<?php $this->render_classes( 'pie-easy-options-block', $block_class ) ?>">
			<?php $this->render_manifest() ?>
			<div class="pie-easy-options-mesg">
				<!-- flash messages for this option will render here -->
			</div><?php
	}

	/**
	 * Renders option container closing tag
	 */
	public function render_end()
	{
		// end rendering ?>
		</div><?php
	}

	/**
	 * Render form input label
	 */
	public function render_label()
	{
		// begin rendering label tag ?>
		<label for="<?php $this->render_name() ?>" title="<?php print esc_attr( $this->get_current()->title ) ?>"><?php print esc_attr( $this->get_current()->title ) ?></label><?php
	}

	/**
	 * Render the field
	 */
	final public function render_field()
	{
		// call the option's field rendering method
		return $this->get_current()->render_field();
	}

	/**
	 * Render a simple form input tag
	 *
	 * @param string $type A valid form input element "type" attribute value (see HTML spec)
	 */
	public function render_input( $type )
	{ ?>
		<input type="<?php print $type ?>" name="<?php $this->render_name() ?>" id="<?php print esc_attr(  $this->get_current()->field_id ) ?>" class="<?php print esc_attr( $this->get_current()->field_class ) ?>" value="<?php print esc_attr( $this->get_current()->get() ) ?>" /> <?php
	}

	/**
	 * Render a group of inputs with the same name
	 *
	 * @param string $type Valid types are 'checkbox' and 'radio'
	 * @param array $field_options
	 * @param mixed $selected_value
	 */
	public function render_input_group( $type, $field_options = null, $selected_value = null )
	{
		// field options defaults to rendered option config
		if ( empty( $field_options ) ) {
			$field_options = $this->get_current()->field_options;
		}

		// select value defaults to rendered option setting
		if ( empty( $selected_value ) ) {
			$selected_value = $this->get_current()->get();
		}

		// force the selected value to an array
		if ( !is_array( $selected_value ) ) {
			$selected_value = array( $selected_value );
		}

		if ( is_array( $field_options ) ) {
			// render div wrapper if applicable
			if ( $this->get_current()->field_id ) { ?>
				<div id="<?php print $this->get_current()->field_id ?>"><?php
			}
			// loop through all field options
			foreach ( $field_options as $value => $display ) {
				$loop = ($loop) ? $loop + 1 : 1;
				$checked = ( in_array( $value, $selected_value ) ) ? ' checked=checked' : null; ?>
				<input type="<?php print $type ?>" name="<?php $this->render_name() ?><?php if ( $type == 'checkbox' ): ?>[]<?php endif; ?>" value="<?php print esc_attr( $value ) ?>"<?php print $checked ?> /><label for="<?php print $element_id ?>"><?php print $display ?></label><?php
			}
			// close div wrapper if applicable
			if ( $this->get_current()->field_id ) { ?>
				</div><?php
			}
		} else {
			throw new Exception( sprintf( 'The "%s" option has no array of field options to render', $this->get_current()->name ) );
		}
	}

	/**
	 * Render a select tag
	 *
	 * @param array $field_options Multi-dimensional array of options to render (value => description)
	 * @param mixed $selected_value The value to render as selected
	 */
	public function render_select( $field_options = null, $selected_value = null )
	{
		// field options defaults to rendered option config
		if ( empty( $field_options ) ) {
			$field_options = $this->get_current()->field_options;
		}

		// select value defaults to rendered option setting
		if ( empty( $selected_value ) ) {
			$selected_value = $this->get_current()->get();
		} ?>

		<select name="<?php $this->render_name() ?>" id="<?php print esc_attr( $this->get_current()->field_id ) ?>" class="<?php print esc_attr( $this->get_current()->field_class ) ?>">
			<option value="">--- Select One ---</option>
			<?php foreach ( $field_options as $value => $text ):
				$selected = ( $value == $selected_value ) ? ' selected="selected"' : null; ?>
				<option value="<?php print esc_attr( $value ) ?>"<?php print $selected ?>><?php print esc_html( $text ) ?></option>
			<?php endforeach; ?>
		</select><?php
	}

	/**
	 * Render textarea input tag
	 */
	public function render_textarea()
	{ ?>
		<textarea name="<?php $this->render_name() ?>" id="<?php print esc_attr(  $this->get_current()->field_id ) ?>" class="<?php print esc_attr( $this->get_current()->field_class ) ?>" rows="5" cols="50"><?php print esc_attr( $this->get_current()->get() ) ?></textarea> <?php
	}

	/**
	 * Render a string representation of the date the option was last updated
	 *
	 * @param string $format
	 */
	public function render_date_updated( $format = 'F j, Y, g:i a' )
	{
		$time_updated = $this->get_current()->get_meta( Pie_Easy_Options_Option::META_TIME_UPDATED );

		if ( $time_updated ) {
			print date( $format, $time_updated );
		} else {
			print __('Never', pie_easy_text_domain);
		}
	}

	/**
	 * Render save all options button
	 *
	 * @param string $class
	 */
	public function render_save_all( $class = null )
	{
		// begin rendering ?>
		<a class="<?php $this->merge_classes('pie-easy-options-save', 'pie-easy-options-save-all', $class) ?>" href="#">
			<?php _e( 'Save All', pie_easy_text_domain ); ?>
		</a><?php
	}
	
	/**
	 * Render save one option button
	 *
	 * @param string $class
	 */
	public function render_save_one( $class = null )
	{
		// begin rendering ?>
		<a class="<?php $this->merge_classes('pie-easy-options-save', 'pie-easy-options-save-one', $class) ?>" href="#<?php $this->render_name() ?>">
			<?php _e( 'Save', pie_easy_text_domain ); ?>
		</a><?php
	}

	/**
	 * Render a hidden input which appends the current option name to the manifest
	 */
	final public function render_manifest()
	{
		// begin rendering ?>
		<input type="hidden" name="<?php print self::FIELD_MANIFEST ?>[]" value="<?php $this->render_name() ?>" /><?php
	}
}

?>
