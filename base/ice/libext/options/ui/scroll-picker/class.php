<?php
/**
 * ICE API: option extensions, UI scroll picker class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage options
 * @since 1.0
 */

ICE_Loader::load( 'components/options/component' );

/**
 * UI Scroll Picker
 *
 * This option relies heavily on the jQuery UI slider widget
 *
 * @link http://jqueryui.com/demos/slider/
 * @package ICE-extensions
 * @subpackage options
 * @property-read string $item_width
 * @property-read string $item_height
 * @property-read string $item_margin
 */
class ICE_Exts_Options_Ui_Scroll_Picker
	extends ICE_Option
{
	/**
	 */
	protected function init()
	{
		// run parent
		parent::init();

		// initilaize directives
		$this->item_width = null;
		$this->item_height = null;
		$this->item_margin = null;
	}
	
	/**
	 */
	public function init_scripts()
	{
		parent::init_scripts();

		if ( is_admin() ) {
			// need scrollpane helper
			wp_enqueue_script( 'ice-scrollpane' );
		}
	}

	/**
	 */
	public function configure()
	{
		// RUN PARENT FIRST!
		parent::configure();

		// get config
		$config = $this->config();

		// item width
		if ( isset( $config->item_width ) ) {
			$this->item_width = (string) $config->item_width;
		}

		// item height
		if ( isset( $config->item_height ) ) {
			$this->item_height = (string) $config->item_height;
		}

		// item margin
		if ( isset( $config->item_margin ) ) {
			$this->item_margin = (string) $config->item_margin;
		}
	}

	/**
	 */
	public function get_template_vars()
	{
		// new script helper
		$script = new ICE_Script();
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
