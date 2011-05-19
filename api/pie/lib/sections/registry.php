<?php
/**
 * PIE API: sections registry
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage sections
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/registry', 'sections/factory' );

/**
 * Make keeping track of sections easy
 *
 * @package PIE
 * @subpackage section
 */
abstract class Pie_Easy_Sections_Registry extends Pie_Easy_Registry
{
	/**
	 * Return all registered child sections of a section
	 *
	 * This adheres to parent settings in the sections ini file
	 *
	 * @param Pie_Easy_Sections_Section $section The section object whose children you want to get
	 * @return array
	 */
	public function get_children( Pie_Easy_Sections_Section $section )
	{
		// the sections that will be returned
		$sections = array();

		// find all registered sections where parent is the target section
		foreach ( $this->get_all() as $section_i ) {
			if ( $section->is_parent_of( $section_i ) ) {
				$sections[] = $section_i;
			}
		}

		return $sections;
	}

	/**
	 * Get sections that should behave as a root section
	 *
	 * This method mostly exists as a helper to use when rendering menus
	 *
	 * @param array $section_names An array of section names to include, defaults to all
	 * @return array
	 */
	public function get_roots( $section_names = array() )
	{
		// sections to be returned
		$sections = array();

		// loop through all registered sections
		foreach ( $this->get_all() as $section ) {
			// filter on section names
			if ( empty( $section_names ) || in_array( $section->name, $section_names, true ) ) {
				$sections[] = $section;
			}
		}

		// don't return sections who have a parent in the result
		foreach( $sections as $key => $section_i ) {
			foreach( $sections as $section_ii ) {
				if ( $section_ii->is_parent_of( $section_i ) ) {
					unset( $sections[$key] );
				}
			}
		}

		return $sections;
	}

	/**
	 * Load a section into the registry
	 *
	 * @param string $section_name
	 * @param string $section_config
	 * @return boolean
	 */
	protected function load_config_single( $section_name, $section_config )
	{
		// create new section
		$section = $this->policy()->factory()->create(
			'default',
			$this->loading_theme,
			$section_name,
			$section_config['title']
		);

		// css class
		if ( isset( $section_config['class'] ) ) {
			$section->set_class( $section_config['class'] );
		}

		// css title class
		if ( isset( $section_config['class_title'] ) ) {
			$section->set_class_title( $section_config['class_title'] );
		}

		// css content class
		if ( isset( $section_config['class_content'] ) ) {
			$section->set_class_content( $section_config['class_content'] );
		}

		// section parent
		if ( isset( $section_config['parent'] ) ) {
			$section->set_parent( $section_config['parent'] );
		}

		// register it
		return $this->register( $section );
	}

}

?>
