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

ICE_Loader::load( 'base/component' );

/**
 * Make an option section easy
 *
 * @package ICE-components
 * @subpackage sections
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
	 * The CSS class for the content of this section
	 * 
	 * @var string
	 */
	protected $class_content;

	/**
	 * The CSS class for the title of this section
	 * 
	 * @var string
	 */
	protected $class_title;

	/**
	 */
	public function get_property( $name )
	{
		switch ( $name ) {
			case 'class_content':
			case 'class_title':
				return $this->$name;
			default:
				return parent::get_property( $name );
		}
	}

	/**
	 */
	protected function configure()
	{
		// RUN PARENT FIRST!
		parent::configure();

		// import settings
		$this->import_settings( array(
			'class_content',
			'class_title'
		));
	}

	/**
	 * Add component to this section's content
	 *
	 * @param ICE_Component $component
	 */
	public function add_component( ICE_Component $component )
	{
		// does component section match my name?
		if ( $component->get_property( 'section' ) == $this->get_property( 'name' ) ) {
			// yep, add to components array
			$this->__components__[] = $component;
		} else {
			// not good
			throw new Exception(
				sprintf( 'The component "%s" is not assigned to the section "%s"', $component->get_property( 'name' ), $this->get_property( 'name' ) ) );
		}
	}

	/**
	 * Return array containing all components which have been added.
	 *
	 * @return array
	 */
	public function get_components()
	{
		return $this->__components__;
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
