<?php
/**
 * PIE API: options renderer class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'utils/docs' );

/**
 * Make rendering options easy
 *
 * @package PIE
 * @subpackage options
 */
abstract class Pie_Easy_Options_Option_Renderer
{
	/**
	 * All options that have been rendered
	 *
	 * @var array
	 */
	private $options_rendered = array();

	/**
	 * The current option being rendered
	 *
	 * @var Pie_Easy_Options_Option
	 */
	private $option;

	/**
	 * Return true if the option being rendered has documentation to render
	 *
	 * @return boolean
	 */
	final protected function has_documentation()
	{
		return ( $this->option->documentation );
	}

	/**
	 * Render an option
	 *
	 * @param Pie_Easy_Options_Option $option The option to render
	 * @param boolean $output Set to false to return results instead of printing
	 * @return string|void
	 */
	final public function render( Pie_Easy_Options_Option $option, $output = true )
	{
		// check feature support
		if ( $option->supported() ) {

			// set as currently rendered option
			$this->option = $option;
			$this->option->enable_post_override();

			// handle output buffering if applicable
			if ( $output === false ) {
				ob_start();
			}

			// render the option
			$this->render_option();
			$this->options_rendered[] = $option;

			// return results if output buffering is on
			if ( $output === false ) {
				return ob_get_clean();
			}
		}
	}

	/**
	 * Render option label, input, and description wrapped in a container
	 *
	 * This is a very basic implementation. In most cases you will want to override
	 * this to generate custom markup.
	 */
	protected function render_option()
	{
		// start rendering ?>
		<div class="<?php $this->render_classes( 'pie-easy-options-wrapper' ) ?>">
			<?php $this->render_label() ?>
			<p class="pie-easy-options-description">
				<?php $this->render_description() ?>
			</p>
			<div class="pie-easy-options-field">
				<?php $this->render_field() ?>
			</div>
		</div><?php
	}

	/**
	 * Render the option name
	 *
	 * This is useful when using it as part of an attribute
	 */
	public function render_name()
	{
		print esc_attr( $this->option->name );
	}

	/**
	 * Render wrapper classes
	 *
	 * @param string $class,...
	 */
	public function render_classes()
	{
		// get unlimited number of class args
		$classes = func_get_args();

		// append custom class if set
		if ( $this->option->class ) {
			$classes[] = $this->option->class;
		}

		// render them all delimited with a space
		print join( ' ', $classes );
	}

	/**
	 * Render form input label
	 */
	public function render_label()
	{ ?>
		<label class="pie-easy-options-title" for="<?php $this->render_name() ?>" title="<?php print esc_attr( $this->option->title ) ?>"><?php print esc_attr( $this->option->title ) ?></label><?php
	}

	/**
	 * Render form input description
	 */
	public function render_description()
	{
		print esc_attr( $this->option->description );
	}

	/**
	 * Render the field
	 */
	final protected function render_field()
	{
		// call the option's field rendering method
		return $this->option->render_field( $this );
	}

	/**
	 * Render a simple form input tag
	 *
	 * @param string $type A valid form input element "type" attribute value (see HTML spec)
	 */
	public function render_input( $type )
	{ ?>
		<input type="<?php print $type ?>" name="<?php $this->render_name() ?>" id="<?php print esc_attr(  $this->option->field_id ) ?>" class="<?php print esc_attr( $this->option->field_class ) ?>" value="<?php print esc_attr( $this->option->get() ) ?>" /> <?php
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
			$field_options = $this->option->field_options;
		}

		// select value defaults to rendered option setting
		if ( empty( $selected_value ) ) {
			$selected_value = $this->option->get();
		}

		// force the selected value to an array
		if ( !is_array( $selected_value ) ) {
			$selected_value = array( $selected_value );
		}

		if ( is_array( $field_options ) ) {
			// render div wrapper if applicable
			if ( $this->option->field_id ) { ?>
				<div id="<?php print $this->option->field_id ?>"><?php
			}
			// loop through all field options
			foreach ( $field_options as $value => $display ) {
				$loop = ($loop) ? $loop + 1 : 1;
				$checked = ( in_array( $value, $selected_value ) ) ? ' checked=checked' : null; ?>
				<input type="<?php print $type ?>" name="<?php $this->render_name() ?><?php if ( $type == 'checkbox' ): ?>[]<?php endif; ?>" value="<?php print esc_attr( $value ) ?>"<?php print $checked ?> /><label for="<?php print $element_id ?>"><?php print $display ?></label><?php
			}
			// close div wrapper if applicable
			if ( $this->option->field_id ) { ?>
				</div><?php
			}
		} else {
			throw new Exception( sprintf( 'The "%s" option has no array of field options to render', $this->option->name ) );
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
			$field_options = $this->option->field_options;
		}

		// select value defaults to rendered option setting
		if ( empty( $selected_value ) ) {
			$selected_value = $this->option->get();
		} ?>

		<select name="<?php $this->render_name() ?>" id="<?php print esc_attr( $this->option->field_id ) ?>" class="<?php print esc_attr( $this->option->field_class ) ?>">
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
		<textarea name="<?php $this->render_name() ?>" id="<?php print esc_attr(  $this->option->field_id ) ?>" class="<?php print esc_attr( $this->option->field_class ) ?>" rows="5" cols="50"><?php print esc_attr( $this->option->get() ) ?></textarea> <?php
	}

	/**
	 * Render a string representation of the date the option was last updated
	 *
	 * @param string $format
	 */
	public function render_date_updated( $format = 'F j, Y, g:i a' )
	{
		$time_updated = $this->option->get_meta( Pie_Easy_Options_Option::META_TIME_UPDATED );

		if ( $time_updated ) {
			print date( $format, $time_updated );
		} else {
			print __('Never', pie_easy_text_domain);
		}
	}

	/**
	 * Render documentation for this option
	 *
	 * @param array $doc_dirs Directory paths under which to search for doc page file
	 */
	final protected function render_documentation( $doc_dirs )
	{
		// is documentation set?
		if ( $this->option->documentation ) {
			// boolean value?
			if ( is_numeric( $this->option->documentation ) ) {
				// use auto naming?
				if ( (boolean) $this->option->documentation == true ) {
					// yes, page is option name
					$page = 'options/' . $this->option->name;
				} else {
					// no, documentation disabled
					return;
				}
			} else {
				// page name was set manually
				$page = $this->option->documentation;
			}

			// new easy doc object
			$doc = new Pie_Easy_Docs( $doc_dirs, $page );

			// publish it!
			$doc->publish();
		}
	}

	/**
	 * Render sample code for this option
	 *
	 * @todo get rid of these infinity functions
	 */
	final protected function render_sample_code()
	{
// begin rendering ?>
<strong>Test if option is set</strong>
<code>&lt;?php if ( infinity_option( '<?php print $this->option->name ?>' ) ): ?&gt;
    <?php print $this->option->name ?> has a value
&lt;?php endif; ?&gt;</code>

<strong>Echo an option value</strong>
<code>&lt;?php echo infinity_option( '<?php print $this->option->name ?>' ); ?&gt;</code><?php

		// special uploader functions
		if ( $this->option instanceof Pie_Easy_Exts_Option_Upload ) {
// begin rendering ?>
<strong>Echo option as image URL</strong>
<code>&lt;img src="&lt;?php echo infinity_option_image_url( '<?php print $this->option->name ?>' ); ?&gt;"&gt;</code><?php
		}
	}

	/**
	 * Render a hidden input which is a serialized array of all option names that were rendered
	 *
	 * @param boolean $output
	 */
	final public function render_manifest( $output = true )
	{
		$option_names = array();

		foreach ( $this->options_rendered as $option ) {
			$option_names[] = $option->name;
		}

		$html = sprintf(
			'<input type="hidden" name="_manifest_" value="%s" />',
			esc_attr( implode( ',', $option_names ) )
		);

		if ( $output ) {
			print $html;
		} else {
			return $html;
		}
	}
}

?>
