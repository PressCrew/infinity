<?php
/**
 * ICE API: section class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-components
 * @subpackage sections
 * @since 1.0
 */

ICE_Loader::load( 'base' );

/**
 * Make an option section easy
 *
 * @package ICE-components
 * @subpackage sections
 * @property-read string $class_title The CSS class for the title of this section
 * @property-read string $class_content The CSS class for the content of this section
 */
abstract class ICE_Section extends ICE_Component
{
	/**
	 * Components to render
	 *
	 * @var array
	 */
	private $__components__;

	/**
	 */
	protected function init()
	{
		// run parent
		parent::init();

		// init directives
		$this->class_title = null;
		$this->class_content = null;
	}
	
	/**
	 */
	public function configure()
	{
		// RUN PARENT FIRST!
		parent::configure();

		// get config
		$config = $this->config();

		// css title class
		if ( isset( $config->class_title ) ) {
			$this->class_title = $config->class_title;
		}

		// css content class
		if ( isset( $config->class_content ) ) {
			$this->class_content = $config->class_content;
		}
	}

	/**
	 * Add component to this section's content
	 *
	 * @param ICE_Component $component
	 */
	public function add_component( ICE_Component $component )
	{
		// does component section match my name?
		if ( $component->section == $this->name ) {
			// yep, add to components array
			$this->__components__[] = $component;
		} else {
			// not good
			throw new Exception(
				sprintf( 'The component "%s" is not assigned to the section "%s"', $component->name, $this->name ) );
		}
	}

	/**
	 * Render all components
	 */
	public function render_components()
	{
		foreach ( $this->__components__ as $component ) {
			$component->render();
		}
	}
}

?>
