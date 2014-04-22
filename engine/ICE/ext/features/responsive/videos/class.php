<?php
/**
 * ICE API: feature extensions, responsive videos feature class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2012 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage features
 * @since 1.0
 */

ICE_Loader::load( 'components/features/component' );

/**
 * Responsive videos feature
 *
 * @link https://github.com/davatron5000/FitVids.js
 * @package ICE-extensions
 * @subpackage features
 */
class ICE_Ext_Feature_Responsive_Videos
	extends ICE_Feature
{
	/**
	 * Custom selector for additional video players
	 *
	 * @var string
	 */
	protected $custom_selector;
	
	/**
	 * The selector to target
	 * 
	 * @var string
	 */
	protected $target_selector;

	/**
	 */
	public function get_property( $name )
	{
		switch ( $name ) {
			case 'custom_selector':
			case 'target_selector':
				return $this->$name;
			default:
				return parent::get_property( $name );
		}
	}
	
	/**
	 */
	public function init()
	{
		// run parent init method
		parent::init();

		// setup actions
		add_action( 'ice_init_blog', array( $this, 'setup_scripts' ) );

		// template actions
		add_action( 'open_body', array( $this, 'render' ) );
	}

	/**
	 */
	protected function configure()
	{
		// RUN PARENT FIRST!
		parent::configure();

		// import settings
		$this->import_settings( array(
			'custom_selector',
			'target_selector'
		));
	}

	/**
	 * Setup scripts.
	 */
	public function setup_scripts()
	{
		ice_enqueue_script( 'jquery-fitvids' );
	}
	
	/**
	 */
	public function get_template_vars()
	{
		// new script helper
		$script = new ICE_Script();
		$logic = $script->logic( 'vars' );

		// add variables
		$logic->av( 'customSelector', $this->custom_selector );

		// return vars
		return array(
			'selector' => $this->target_selector,
			'options' => $logic->export_variables(true)
		);
	}
}
