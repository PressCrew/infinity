<?php
/**
 * PIE API: option extensions, UI scroll picker class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'components/options/component' );

/**
 * UI Scroll Picker
 *
 * This option relies heavily on the jQuery UI slider widget
 *
 * @link http://jqueryui.com/demos/slider/
 * @package PIE-extensions
 * @subpackage options
 * @property-read string $item_width
 * @property-read string $item_height
 * @property-read string $item_margin
 */
class Pie_Easy_Exts_Options_Ui_Scroll_Picker
	extends Pie_Easy_Options_Option
{
	/**
	 */
	public function init_scripts()
	{
		parent::init_scripts();
		wp_enqueue_script( 'pie-easy-scrollpane' );
	}

	/**
	 */
	public function configure( $config, $theme )
	{
		// RUN PARENT FIRST!
		parent::configure( $config, $theme );

		// item width
		if ( isset( $config['item_width'] ) ) {
			$this->directives()->set( $theme, 'item_width', $config['item_width'] );
		}

		// item height
		if ( isset( $config['item_height'] ) ) {
			$this->directives()->set( $theme, 'item_height', $config['item_height'] );
		}

		// item margin
		if ( isset( $config['item_margin'] ) ) {
			$this->directives()->set( $theme, 'item_margin', $config['item_margin'] );
		}
	}

	/**
	 */
	public function get_template_vars()
	{
		// new script helper
		$script = new Pie_Easy_Script();
		$options = $script->logic();

		// add variables
		$options->av( 'value', $this->get() );
		$options->av( 'itemWidth', $this->item_width );
		$options->av( 'itemHeight', $this->item_height );
		$options->av( 'itemMargin', $this->item_margin );

		// return vars
		return array(
			'value' => $this->get(),
			'scroll_options' => $options->export_variables(true),
			'field_options' => $this->field_options->to_array()
		);
	}

	/**
	 * Render the template output for a field option
	 *
	 * @param mixed $value
	 */
	public function render_field_option( $value )
	{
		print esc_html( $this->field_options->item_at( $value ) );
	}
}

?>
