<?php
/**
 * ICE API: option extensions, UI slider class file
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
 * UI Slider option
 *
 * This option is a wrapper for the jQuery UI slider widget
 *
 * @link http://jqueryui.com/demos/slider/
 * @package ICE-extensions
 * @subpackage options
 */
class ICE_Ext_Option_Ui_Slider
	extends ICE_Option
{
	/**
	 * @var boolean|string|integer
	 */
	protected $animate;

	/**
	 * @var string
	 */
	protected $delimiter;

	/**
	 * @var string
	 */
	protected $label;

	/**
	 * @var integer
	 */
	protected $max;

	/**
	 * @var integer
	 */
	protected $min;

	/**
	 * @var string
	 */
	protected $orientation;

	/**
	 * @var string
	 */
	protected $prefix;

	/**
	 * @var boolean
	 */
	protected $range;

	/**
	 * @var integer
	 */
	protected $step;

	/**
	 * @var string
	 */
	protected $suffix;

	/**
	 */
	public function get_property( $name )
	{
		switch ( $name ) {
			case 'animate':
			case 'delimiter':
			case 'label':
			case 'max':
			case 'min':
			case 'orientation':
			case 'prefix':
			case 'range':
			case 'step':
			case 'suffix':
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
		$this->import_settings(
			array(
				'animate',
				'delimiter',
				'label',
				'max',
				'min',
				'orientation',
				'prefix',
				'range',
				'step',
				'suffix'
			),
			array(
				'max' => 'integer',
				'min' => 'integer',
				'range' => 'boolean',
				'step' => 'integer'
			)
		);
	}
	
	/**
	 */
	public function init()
	{
		// run parent
		parent::init();

		// setup dash assets
		add_action( 'ice_init_dash', array( $this, 'setup_dash_assets' ) );
	}

	/**
	 * Setup dashboard assets.
	 */
	public function setup_dash_assets()
	{
		// enqueue dependencies
		ice_enqueue_style( 'ice-ext-dash' );
		ice_enqueue_script( 'ice-slider' );
	}
	
	/**
	 */
	public function get_template_vars()
	{
		// new script helper
		$script = new ICE_Script();
		$logic = $script->logic( 'vars' );

		// set value(s)
		$value = $this->get();

		if ( is_array( $value ) ) {
			$logic->av( 'values', $value );
		} elseif ( is_numeric( $value ) ) {
			$logic->av( 'value', $value );
		} else {
			$logic->av( 'value', 0 );
		}

		// range needs special handling
		if ( $this->range ) {
			$logic->av( 'range', true );
		}
		
		// add variables
		$logic->av( 'animate', (boolean) $this->animate );
		$logic->av( 'max', $this->max );
		$logic->av( 'min', $this->min );
		$logic->av( 'orientation', $this->orientation );
		$logic->av( 'step', $this->step );
		$logic->av( 'prefix', $this->prefix );
		$logic->av( 'suffix', $this->suffix );
		$logic->av( 'delimiter', $this->delimiter );

		// return vars
		return array(
			'options' => $logic->export_variables(true),
			'label' => ( $this->label ) ? $this->label : __( 'Current selection:', 'infinity-engine' )
		);
	}
}
