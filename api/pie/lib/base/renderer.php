<?php
/**
 * PIE API: base renderer class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/policeable' );

/**
 * Make rendering concrete components easy
 *
 * @package PIE
 * @subpackage base
 */
abstract class Pie_Easy_Renderer extends Pie_Easy_Policeable
{
	/**
	 * The current component being rendered
	 *
	 * @var Pie_Easy_Component
	 */
	private $current;

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
	final protected function get_current()
	{
		return $this->current;
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
	final protected function has_documentation()
	{
		return ( $this->current->documentation );
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
			$this->current = $component;

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
	 * Render the name
	 */
	public function render_name()
	{
		print esc_attr( $this->current->name );
	}

	/**
	 * Render the title
	 */
	public function render_title()
	{
		print esc_html( $this->current->title );
	}

	/**
	 * Render form input description
	 */
	public function render_description()
	{
		print esc_html( $this->current->description );
	}

	/**
	 * Render wrapper classes
	 *
	 * @param string $class,...
	 */
	public function render_classes()
	{
		// get unlimited number of class args
		$classes = func_get_args();

		// append custom class if set
		if ( $this->current->class ) {
			$classes[] = $this->current->class;
		}

		// render them all delimited with a space
		print esc_attr( join( ' ', $classes ) );
	}

	/**
	 * Render documentation for this component
	 *
	 * @param array $doc_dirs Directory paths under which to search for doc page file
	 */
	final protected function render_documentation( $doc_dirs )
	{
		// is documentation set?
		if ( $this->current->documentation ) {
			// boolean value?
			if ( is_numeric( $this->current->documentation ) ) {
				// use auto naming?
				if ( (boolean) $this->current->documentation == true ) {
					// yes, page is component name
					$page = $this->policy()->get_handle() . '/' . $this->current->name;
				} else {
					// no, documentation disabled
					return;
				}
			} else {
				// page name was set manually
				$page = $this->current->documentation;
			}

			// new easy doc object
			$doc = new Pie_Easy_Docs( $doc_dirs, $page );

			// publish it!
			$doc->publish();
		}
	}

	/**
	 * Render component output
	 *
	 * @return void
	 */
	abstract protected function render_output();
	
	/**
	 * Render sample code for this component
	 * 
	 * @todo this should be defined by an interface
	 */
	protected function render_sample_code() {}

}

?>
