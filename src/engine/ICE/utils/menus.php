<?php
/**
 * ICE API: menu helpers class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2012-2015 CUNY Academic Commons, Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage utils
 * @since 1.2
 */

/**
 * Utility class for managing menus programatically.
 */
class ICE_Menu_Manager
{
	/**
	 * Internal menu name.
	 * 
	 * @var string
	 */
	private $name;

	/**
	 * Internal menu id.
	 *
	 * @var integer
	 */
	private $id = null;

	/**
	 * The nav menu object;
	 *
	 * @var stdClass
	 */
	private $obj;

	/**
	 * Constructor.
	 *
	 * @param string $name Internal menu name.
	 */
	public function __construct( $name )
	{
		// set the name
		$this->name = $name;
		
		// populate local vars
		$this->populate();
	}

	/**
	 * Populate the id and object properties.
	 */
	final protected function populate()
	{
		// try to get existing menu object
		$this->obj = wp_get_nav_menu_object( $this->name );

		// get an object?
		if ( true === $this->obj instanceof stdClass ) {
			// yep, grab the id
			$this->id = (int) $this->obj->term_id;
		}
	}

	/**
	 * Return the id of this menu, if it exists.
	 *
	 * @return int
	 */
	public function get_id()
	{
		return $this->id;
	}

	/**
	 * Returns true if the menu exists;
	 *
	 * @return boolean
	 */
	public function exists()
	{
		return ( null !== $this->id );
	}

	/**
	 * Try to register this menu if it doesn't exist yet.
	 *
	 * @param type $description
	 * @param type $parent
	 * @return bool
	 */
	public function register( $description = null, $parent = null )
	{
		// already exists?
		if ( false === $this->exists() ) {

			// nope, set up menu data array
			$menu_data = array(
				'menu-name' => $this->name,
				'description' => $description,
				'parent' => $parent
			);

			// try to create it
			$menu_id = wp_update_nav_menu_object( 0, $menu_data );

			// did we get an id?
			if ( true === is_int( $menu_id ) ) {
				// yep, update properties
				$this->populate();
				// success
				return true;
			}
		}

		// not registered
		return false;
	}

	/**
	 * Add an item to this menu.
	 *
	 * @param array $menu_item_data Array of valid menu item data settings.
	 * @param bool $short_keys Passed item data keys do not have the 'menu-item-' key prefix.
	 * @return int|false
	 */
	public function add_item( $menu_item_data = array(), $short_keys = true )
	{
		// make sure menu exists
		if ( true === $this->exists() ) {

			// using short keys?
			if ( true === $short_keys ) {
				// yep, loop all args and normalize keys
				foreach( $menu_item_data as $key => $val ) {
					// prefix key with menu item key prefix
					$menu_item_data[ 'menu-item-' . $key ] = $val;
					// remove short key
					unset( $menu_item_data[ $key ] );
				}
			}

			// defaults
			$defaults = array(
				'menu-item-status' => 'publish'
			);
			
			// apply defaults
			$final_data = wp_parse_args( $menu_item_data, $defaults );

			// add nav item to menu
			$menu_item_id = wp_update_nav_menu_item( $this->id, 0, $final_data );
			
			// did we get an int?
			if ( true === is_int( $menu_item_id ) ) {
				// yes, return it
				return $menu_item_id;
			}
		}
		
		// failed to add item
		return false;
	}

	/**
	 * Add a BuddyPress directory page as a menu item.
	 *
	 * @param string $component The component slug you want to add.
	 * @param array $menu_item_data An array of valid menu item data. At least 'title' is required.
	 * @return int|bool
	 */
	public function add_bp_page( $component, $menu_item_data )
	{
		// make sure bp function we need exists
		if ( true === function_exists( 'bp_core_get_directory_pages' ) ) {
			// get bp pages
			$bp_pages = bp_core_get_directory_pages();
		} else {
			// missing function bail
			return false;
		}

		// does the page actually exist?
		if ( true === property_exists( $bp_pages, $component ) ) {

			// yep, set up typical bp defaults
			$defaults = array(
				'status' => 'publish',
				'type' => 'post_type',
				'object' => 'page',
				'object-id' => $bp_pages->{$component}->id,
				'classes' => 'icon-' . $component,
			);
				
			// apply defaults
			$final_data = wp_parse_args( $menu_item_data, $defaults );

			// add the menu item
			return $this->add_item( $final_data );
		}

		// page not added to menu
		return false;
	}

	/**
	 * Try to add this menu to the given location.
	 * 
	 * @param string $location
	 * @param bool $force
	 * @return boolean
	 */
	public function add_location( $location, $force = false )
	{
		// make sure menu exists
		if ( true === $this->exists() ) {

			// get location settings
			$locations = get_theme_mod( 'nav_menu_locations' );

			// determine if we should set the location
			if (
				// force override?
				true === $force ||
				// is our menu location set yet?
				true === empty( $locations[ $location ] )
			) {
				// yes, set it
				$locations[ $location ] = $this->id;
				// update theme mode
				set_theme_mod( 'nav_menu_locations', $locations );
				// we *tried* to update it, assume it saved
				return true;
			}
		}

		// not added to location
		return false;
	}
}
