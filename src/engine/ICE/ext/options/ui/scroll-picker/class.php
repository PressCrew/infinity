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
 */
class ICE_Ext_Option_Ui_Scroll_Picker
	extends ICE_Option
{
	/**
	 * @var string
	 */
	protected $item_height;

	/**
	 * @var string
	 */
	protected $item_margin;

	/**
	 * @var string
	 */
	protected $item_width;
	
	/**
	 */
	public function get_property( $name )
	{
		switch ( $name ) {
			case 'item_height':
			case 'item_margin':
			case 'item_width':
				return $this->$name;
			default:
				return parent::get_property( $name );
		}
	}

	/**
	 */
	protected function configure()
	{
		// run parent first
		parent::configure();

		// import settings
		$this->import_settings( array(
			'item_height',
			'item_margin',
			'item_width'
		));
	}
	
	/**
	 */
	public function init()
	{
		// run parent
		parent::init();

		// setup admin scripts
		add_action( 'ice_init_dash', array( $this, 'setup_dash_scripts' ) );
	}

	/**
	 * Setup scripts.
	 */
	public function setup_dash_scripts()
	{
		// need scrollpane helper
		ice_enqueue_script( 'ice-scrollpane' );
	}

	/**
	 */
	public function get_template_vars()
	{
		// new script helper
		$script = new ICE_Script();
		$options = $script->logic( 'vars' );

		// add variables
		$options->av( 'value', $this->get() );
		$options->av( 'itemWidth', $this->item_width );
		$options->av( 'itemHeight', $this->item_height );
		$options->av( 'itemMargin', $this->item_margin );

		// return vars
		return array(
			'value' => $this->get(),
			'scroll_options' => $options->export_variables(true),
			'field_options' => $this->get_property( 'field_options' )
		);
	}

	/**
	 * Render the template output for a field option
	 *
	 * @param mixed $value
	 */
	public function render_field_option( $value )
	{
		print esc_html( $this->get_property( 'field_options' )->item_at( $value ) );
	}
}
