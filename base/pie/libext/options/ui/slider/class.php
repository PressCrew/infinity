<?php
/**
 * PIE API: option extensions, UI slider class file
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
 * UI Slider option
 *
 * This option is a wrapper for the jQuery UI slider widget
 *
 * @link http://jqueryui.com/demos/slider/
 * @package PIE-extensions
 * @subpackage options
 * 
 * @property-read boolean|string|integer $animate
 * @property-read integer $max
 * @property-read integer $min
 * @property-read string $orientation
 * @property-read boolean $step
 * @property-read boolean $range
 *
 * @property-read string $label
 * @property-read string $delimiter
 * @property-read string $prefix
 * @property-read string $suffix
 */
class Pie_Easy_Exts_Options_Ui_Slider
	extends Pie_Easy_Options_Option
{
	/**
	 */
	protected function init()
	{
		// run parent
		parent::init();

		// initialize directives
		$this->animate = false;
		$this->max = null;
		$this->min = null;
		$this->orientation = null;
		$this->step = null;
		$this->range = null;
		$this->label = null;
		$this->prefix = null;
		$this->suffix = null;
		$this->delimiter = null;
	}

	/**
	 */
	public function init_scripts()
	{
		parent::init_scripts();
		wp_enqueue_script( 'pie-easy-slider' );
	}

	/**
	 */
	public function configure( Pie_Easy_Init_Config $config )
	{
		// RUN PARENT FIRST!
		parent::configure( $config );

		// animate
		if ( isset( $config->animate ) ) {
			$this->animate = $config->animate;
		}

		// max
		if ( isset( $config->max ) && is_numeric( $config->max ) ) {
			$this->max = $config->max;
		}

		// min
		if ( isset( $config->min ) && is_numeric( $config->min ) ) {
			$this->min = $config->min;
		}

		// orientation
		if ( isset( $config->orientation ) ) {
			$this->orientation = (string) $config->orientation;
		}

		// step
		if ( isset( $config->step ) && is_numeric( $config->step ) ) {
			$this->step = $config->step;
		}

		// range
		if ( isset( $config->range ) && is_numeric( $config->range )  ) {
			$this->range = $config->range;
		}
		
		// value label
		if ( isset( $config->label ) ) {
			$this->label = (string) $config->label;
		}

		// prefix
		if ( isset( $config->prefix ) ) {
			$this->prefix = (string) $config->prefix;
		}

		// suffix
		if ( isset( $config->suffix ) ) {
			$this->suffix = (string) $config->suffix;
		}

		// delimiter (more than one handle)
		if ( isset( $config->delimiter ) ) {
			$this->delimiter = (string) $config->delimiter;
		}
	}

	/**
	 */
	public function get_template_vars()
	{
		// new script helper
		$script = new Pie_Easy_Script();
		$logic = $script->logic();

		// set value(s)
		$value = $this->get();

		if ( $value instanceof Pie_Easy_Map ) {
			$logic->av( 'values', $value->to_array() );
		} elseif ( is_array( $value ) ) {
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
		$logic->av( 'animate', $this->animate );
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
			'label' => ( $this->label ) ? $this->label : __( 'Current selection:', pie_easy_text_domain )
		);
	}
}

?>
