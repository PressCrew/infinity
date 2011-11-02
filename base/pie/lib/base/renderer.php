<?php
/**
 * PIE API: base renderer class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/componentable' );

/**
 * Make rendering concrete components easy
 *
 * @package PIE
 * @subpackage base
 */
abstract class Pie_Easy_Renderer extends Pie_Easy_Componentable
{
	/**
	 * The current component being rendered
	 *
	 * @var Pie_Easy_Component
	 */
	private $component;

	/**
	 * All components that have been rendered
	 *
	 * @var array
	 */
	private $rendered = array();
	
	/**
	 * Return component which is currently being rendered
	 *
	 * @return Pie_Easy_Component
	 */
	final protected function component()
	{
		return $this->component;
	}

	/**
	 * Return components which have been rendered
	 *
	 * @return array
	 */
	final protected function get_rendered()
	{
		return $this->rendered;
	}

	/**
	 * Return true if the component being rendered has documentation to render
	 *
	 * @return boolean
	 */
	final public function has_documentation()
	{
		return ( $this->component->documentation );
	}

	/**
	 * Render a component
	 *
	 * @param Pie_Easy_Component $component The component to render
	 * @param boolean $output Set to false to return results instead of printing
	 * @return string|void
	 */
	final public function render( Pie_Easy_Component $component, $output = true )
	{
		// check feature support
		if ( $component->supported() ) {

			// set as currently rendering component
			$this->component = $component;

			// handle output buffering if applicable
			if ( $output === false ) {
				ob_start();
			}

			// render the output
			$this->render_output();
			$this->rendered[] = $component;

			// return results if output buffering is on
			if ( $output === false ) {
				return ob_get_clean();
			}
		}
	}

	/**
	 * Render a component in bypass mode
	 *
	 * @param Pie_Easy_Component $component The component to render
	 * @return Pie_Easy_Renderer
	 */
	final public function render_bypass( Pie_Easy_Component $component )
	{
		// check feature support
		if ( $component->supported() ) {

			// set as currently rendering component
			$this->component = $component;

			// mark as rendered
			$this->rendered[] = $component;

			// return myself
			return $this;
		}
	}

	/**
	 * Render the name
	 */
	public function render_name()
	{
		print esc_attr( $this->component->name );
	}

	/**
	 * Render the title
	 */
	public function render_title()
	{
		print esc_html( $this->component->title );
	}

	/**
	 * Render form input description
	 */
	public function render_description()
	{
		print esc_html( $this->component->description );
	}

	/**
	 * Render element id
	 */
	public function render_id()
	{
		$args = func_get_args();

		print call_user_func_array(
			array( $this->component, 'get_element_id' ),
			$args
		);
	}

	/**
	 * Render a special one-off class
	 *
	 * @param string $suffix,...
	 */
	public function render_class()
	{
		$args = func_get_args();

		print call_user_func_array(
			array( $this->component, 'get_element_class' ), $args
		);
	}

	/**
	 * Render wrapper classes
	 *
	 * @param string $addtl,...
	 */
	public function render_classes()
	{
		// get unlimited number of class args
		$classes = func_get_args();

		// custom class if set
		if ( $this->component->class ) {
			array_unshift( $classes, $this->component->class );
		}
		
		// component generic class
		array_unshift( $classes, $this->component->get_element_class() );

		// render them all delimited with a space
		print esc_attr( join( ' ', $classes ) );
	}

	/**
	 * Render documentation for this component
	 *
	 * @param array $doc_dirs Directory paths under which to search for doc page file
	 */
	final public function render_documentation( $doc_dirs )
	{
		// is documentation set?
		if ( $this->component->documentation ) {
			// boolean value?
			if ( is_numeric( $this->component->documentation ) ) {
				// use auto naming?
				if ( (boolean) $this->component->documentation == true ) {
					// yes, page is component name
					$page = $this->policy()->get_handle() . '/' . $this->component->name;
				} else {
					// no, documentation disabled
					return;
				}
			} else {
				// page name was set manually
				$page = $this->component->documentation;
			}

			// new easy doc object
			$doc = new Pie_Easy_Docs( $doc_dirs, $page );

			// publish it!
			$doc->publish();
		}
	}

	/**
	 * Merge and print any number of css classes
	 *
	 * @param string $class,...
	 */
	protected function merge_classes()
	{
		// get unlimited number of class args
		$classes = func_get_args();

		// check for empties
		foreach( $classes as $key => $class ) {
			if ( empty( $class ) ) {
				unset( $classes[$key] );
			}
		}
		
		// render them all delimited with a space
		print esc_attr( join( ' ', $classes ) );
	}

	/**
	 * Render component output
	 *
	 * @return void
	 */
	protected function render_output()
	{
		$this->load_template();
	}
	
	/**
	 * Render sample code for this component
	 * 
	 * @todo this should be defined by an interface
	 */
	public function render_sample_code() {}

	/**
	 * Load the component template
	 *
	 * @param integer $ancestor Number of ancestories to skip, including self
	 */
	final public function load_template( $ancestor = 0 )
	{
		// get template vars
		$__tpl_vars__ = $this->component()->get_template_vars();

		// extract?
		if ( is_array( $__tpl_vars__ ) && !empty( $__tpl_vars__ ) ) {
			extract( $__tpl_vars__ );
		}

		// load template
		include( $this->component()->get_template_path( $ancestor ) );
	}

}

?>
