<?php
/**
 * PIE API: feature class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage features
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/component', 'base/styleable', 'utils/files' );

/**
 * Make a feature easy
 *
 * @package PIE
 * @subpackage features
 * @property-read string $template Relative path to feature template file
 */
abstract class Pie_Easy_Features_Feature
	extends Pie_Easy_Component
{
	/**
	 * Config attribute default override delimeter
	 */
	const DEFAULT_TEMPLATE_DIR = 'templates';
	
	/**
	 * Return path to default template
	 *
	 * @return string
	 */
	private function default_template()
	{
		return Pie_Easy_Files::path_build(
			PIE_EASY_LIBEXT_DIR,
			$this->policy()->get_handle(),
			self::DEFAULT_TEMPLATE_DIR,
			$this->policy()->factory()->ext($this) . '.php'
		);
	}

	/**
	 * Extract variables and load the template (if it exists)
	 */
	final public function load_template()
	{
		// try to locate the template
		if ( $this->template ) {
			$__template__ = Pie_Easy_Scheme::instance()->locate_template( $this->template );
		}

		// need default template?
		if ( empty( $__template__ ) ) {
			$__template__ = $this->default_template();
		}

		// get template vars
		$__tpl_vars__ = $this->get_template_vars();

		// extract?
		if ( is_array( $__tpl_vars__ ) && !empty( $__tpl_vars__ ) ) {
			extract( $__tpl_vars__ );
		}

		// load template
		include( $__template__ );
	}

	/**
	 * Return array of variables to extract() for use by the template
	 *
	 * @return array
	 */
	public function get_template_vars()
	{
		// empty array by default
		return array();
	}
	
	/**
	 * Set the stylesheet file path
	 *
	 * @param string $path
	 */
	public function set_stylesheet( $path )
	{
		$this->style()->add_file( $path );
	}

	/**
	 * Set the template file path
	 *
	 * @param string $path
	 */
	public function set_template( $path )
	{
		$this->set_directive( 'template', $path );
	}

	/**
	 * Check if theme supports this feature
	 *
	 * @return boolean
	 */
	public function supported()
	{
		if ( !current_theme_supports( $this->name ) ) {
			return false;
		}

		return parent::supported();
	}
	
}

?>
