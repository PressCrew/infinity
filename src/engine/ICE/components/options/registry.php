<?php
/**
 * ICE API: options registry
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-components
 * @subpackage options
 * @since 1.0
 */

ICE_Loader::load(
	'base/registry',
	'utils/ajax',
	'utils/mod'
);

/**
 * Make keeping track of options easy
 *
 * @package ICE-components
 * @subpackage options
 */
class ICE_Option_Registry extends ICE_Registry
{
	/**
	 * The modifications wrapper instance.
	 * 
	 * @var ICE_Mod
	 */
	private $theme_mod;

	/**
	 */
	public function __construct( ICE_Policy $policy )
	{
		// call parent
		parent::__construct( $policy );
		// init mod wrapper
		$this->theme_mod = new ICE_Mod( 'ice_options' );
	}

	/**
	 * Return the theme modification value for given key.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get_theme_mod( $key, $default )
	{
		// return value from mod wrapper
		return $this->theme_mod->get( $key, $default );
	}

	/**
	 * Set the theme modification value for given key.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function set_theme_mod( $key, $value )
	{
		// call set theme mods as an array
		$this->set_theme_mods( array( $key => $value ) );
	}

	/**
	 * Set the theme modification value for all key=>value pairs.
	 *
	 * @param array $list
	 */
	public function set_theme_mods( $list )
	{
		// loop entire list
		foreach( $list as $key => $value ) {
			// make sure key is a valid option name
			if ( $this->has( $key ) ) {
				// set value using mod wrapper
				$this->theme_mod->set( $key, $value );
			}
		}

		// save all changes
		$this->theme_mod->save();
	}

	/**
	 * Rename the given theme modification key.
	 *
	 * @param string $old_key
	 * @param string $new_key
	 * @return bool
	 */
	public function rename_theme_mod( $old_key, $new_key )
	{
		// call rename theme mods as array
		return $this->rename_theme_mods( array( $old_key => $new_key ) );
	}

	/**
	 * Rename theme modification keys for all old_key => new_key array items.
	 *
	 * @param array $list
	 * @return bool
	 */
	public function rename_theme_mods( $list )
	{
		// loop entire list
		foreach( $list as $old_key => $new_key ) {
			// rename key using mod wrapper
			$this->theme_mod->rename( $old_key, $new_key );
		}

		// save all changes
		return $this->theme_mod->save();
	}

	/**
	 * Return sibling options as an array.
	 *
	 * @param ICE_Option $option
	 * @return array
	 */
	public function get_siblings( ICE_Option $option )
	{
		// options to return
		$options = array();

		// loop all option groups
		foreach ( $this->get_all() as $grp_options ) {
			// loop all group options
			foreach ( $grp_options as $grp_option ) {
				// is passed option parent of this option?
				if ( true === $option->is_parent_of( $grp_option ) ) {
					// yes, push onto stack.
					$options[] = $grp_option;
				}
			}
		}

		return $options;
	}

	/**
	 * Return registered options as an array
	 *
	 * @param ICE_Section $section Limit options to one section by passing a section object
	 * @return array
	 */
	public function get_for_section( ICE_Section $section )
	{
		// options to return
		$options = array();

		// loop option groups
		foreach ( parent::get_all() as $grp_options ) {
			// loop group options
			foreach ( $grp_options as $grp_option ) {
				// do section names match?
				if ( $section->get_name() != $grp_option->get_property( 'section' ) ) {
					continue;
				}
				// add to array
				$options[] = $grp_option;
			}
		}

		// return them
		return $options;
	}

	/**
	 * Return registered options that are valid in a menu
	 *
	 * It does not make sense to list an option in a menu which requires another option,
	 * so this helper method will return an array without them.
	 *
	 * @param ICE_Section $section Limit options to one section
	 * @return array
	 */
	public function get_menu_options( ICE_Section $section = null )
	{		
		// get all options for section
		$options = $this->get_for_section( $section );

		foreach ( $options as $key => $option ) {
			// remove options that require another option
			if ( $option->get_property( 'parent' ) ) {
				unset( $options[$key] );
			}
		}

		return $options;
	}

	/**
	 * Look through POST vars for options from this registry and try to save them
	 *
	 * @return integer Number of options saved
	 */
	public function process_form()
	{
		if ( empty( $_POST ) ) {
			return false;
		} else {

			// check nonce
			check_admin_referer( 'ice_options_update' );

			// get option name to save
			$option_name =
				!empty( $_POST['option_name'] ) ?
				$_POST['option_name'] : null;

			// do a reset if option reset param is set
			$reset_options =
				!empty( $_POST['option_reset'] ) ?
				( (boolean) $_POST['option_reset'] ) : false;

			// keep track of how many were updated
			$save_count = 0;

			// split option name at underscore to get group and name
			$split = explode( '_', $option_name );

			$group = $split[0];
			$name = $split[1];

			// is this option registered?
			if ( $this->has( $name, $group ) ) {
				// look for option name as POST key
				if ( array_key_exists( $option_name, $_POST ) ) {
					// reset?
					if ( $reset_options ) {
						// remove value completely
						$this->theme_mod->remove( $option_name );
					} else {
						// is new value a string?
						if ( is_string( $_POST[ $option_name ] ) ) {
							// yep, strip slashes
							$new_value = stripslashes( $_POST[ $option_name ] );
						} else {
							// nope, use as is
							$new_value = $_POST[ $option_name ];
						}
						// is new value numeric?
						if ( is_numeric( $new_value ) ) {
							// yep, force it to float since it could be an int or a float
							settype( $new_value, 'float' );
						}
						// update it
						$this->theme_mod->set( $option_name, $new_value );
					}
				} else {
					// not in POST, remove it
					$this->theme_mod->remove( $option_name );
				}
				// increment the count
				$save_count++;
			}

			// save the changes
			$this->theme_mod->save();

			// fire after save hook
			do_action( 'ice_option_registry_process_form' );
			
			// done saving
			return $save_count;

		}
	}

	/**
	 * Process the form and generate an AJAX response
	 *
	 * @see process_form
	 */
	public function process_form_ajax()
	{
		// process the form
		$save_count = $this->process_form();

		// any options saved successfuly?
		if ( $save_count == 1 ) {
			ICE_Ajax::responseStd( true, sprintf( __('%d option successfully updated.', 'infinity'), $save_count ) );
		} elseif ( $save_count > 1 ) {
			ICE_Ajax::responseStd( true, sprintf( __('%d options successfully updated.', 'infinity'), $save_count ) );
		} else {
			ICE_Ajax::responseStd( false, __('An error has occurred. No options were updated.', 'infinity') );
		}
	}
}