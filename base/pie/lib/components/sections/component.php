<?php
/**
 * PIE API: section class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-components
 * @subpackage sections
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base' );

/**
 * Make an option section easy
 *
 * @package PIE-components
 * @subpackage sections
 * @property-read string $class_title The CSS class for the title of this section
 * @property-read string $class_content The CSS class for the content of this section
 */
abstract class Pie_Easy_Sections_Section extends Pie_Easy_Component
{
	/**
	 * Components to render
	 *
	 * @var array
	 */
	private $__components__;

	public function configure( $config, $theme )
	{
		// RUN PARENT FIRST!
		parent::configure( $config, $theme );

		// css title class
		if ( isset( $config['class_title'] ) ) {
			$this->directives()->set( $theme, 'class_title', $config['class_title'] );
		}

		// css content class
		if ( isset( $config['class_content'] ) ) {
			$this->directives()->set( $theme, 'class_content', $config['class_content'] );
		}
	}

	/**
	 * Add component to this section's content
	 *
	 * @param Pie_Easy_Component $component
	 */
	public function add_component( Pie_Easy_Component $component )
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
