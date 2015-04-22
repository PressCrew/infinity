<?php
/**
 * ICE API: upgrade helpers class file.
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2014 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage utils
 * @since 1.2
 */

/**
 * Make upgrades easy.
 *
 * @package ICE
 * @subpackage utils
 */
abstract class ICE_Upgrade extends ICE_Base
{
	/**
	 * Modifications instance.
	 * 
	 * @var ICE_Mod
	 */
	private $mod;

	/**
	 * The last version that was stored.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Constructor.
	 */
	final public function __construct()
	{
		// setup mod instance
		$this->mod = new ICE_Mod( 'ice_upgrade' );
		// try to get last stored version
		$this->version = $this->mod->get( 'version' );
		// is a version set?
		if ( empty( $this->version ) ) {
			// nope, set it to 1.0
			$this->version_bump( '1.0' );
		}
	}

	/**
	 * Returns true if comparison of saved version to given version using given operator is successful.
	 *
	 * @uses version_compare()
	 * @param string $version
	 * @param string $operator
	 * @return boolean
	 */
	final public function version_compare( $version, $operator )
	{
		return version_compare( $this->version, $version, $operator );
	}

	/**
	 * Returns true if saved version exactly matches given version.
	 *
	 * @param string $version
	 * @return boolean
	 */
	final public function version_equals( $version )
	{
		// do versions match exactly?
		return $this->version_compare( $version, '==' );
	}

	/**
	 * Bump the version to the given one and save it.
	 *
	 * @param string $version
	 */
	final protected function version_bump( $version )
	{
		// update version property
		$this->version = $version;
		// update stored version
		$this->mod->set( 'version', $version );
		// write changes
		$this->mod->save();
	}

	/**
	 * Perform upgrade tasks.
	 */
	abstract public function run();
}

/**
 * Upgrades required for version 1.1.
 *
 * @package ICE
 * @subpackage utils
 */
class ICE_Upgrade_1_1 extends ICE_Upgrade
{
	/**
	 */
	public function run()
	{
		// is version already 1.1?
		if ( true === $this->version_equals( '1.1' ) ) {
			// yes, all done
			return;
		}

		// version must already be 1.0
		if ( true === $this->version_equals( '1.0' ) ) {
			// no changes, just bump the version
			$this->version_bump( '1.1' );
		}

		// all done
		return;
	}
}

/**
 * Upgrades required for 1.2.
 *
 * @package ICE
 * @subpackage utils
 */
class ICE_Upgrade_1_2 extends ICE_Upgrade_1_1
{
	/**
	 */
	public function run()
	{
		// is version already 1.2?
		if ( true === $this->version_equals( '1.2' ) ) {
			// yes, all done
			return;
		} else {
			// run parent
			parent::run();
		}

		// version must already be 1.1
		if ( true === $this->version_equals( '1.1' ) ) {
			// try to import options
			if ( true === $this->import_options() ) {
				// success, bump the version
				$this->version_bump( '1.2' );
			}
		}

		// all done
		return;
	}

	/**
	 * Import old option values from the settings api into the theme modifications api.
	 *
	 * @return boolean
	 */
	private function import_options()
	{
		// load compat util
		ICE_Loader::load_lib( 'utils/compat' );

		// get options registry
		$registry = ICE_Policy::options()->registry();
		// new values array
		$new_values = array();
		// old api names array
		$api_names = array();

		// loop all option groups
		foreach( $registry->get_all() as $options ) {
			// loop all options
			foreach( $options as $option ) {
				// get deprecated api name
				$api_name = ICE_Compat_Option::get_api_name( $option );
				// get old value
				$old_value = get_option( $api_name );
				// get a value?
				if ( false !== $old_value ) {
					// yes, get option name
					$name = $option->get_name();
					// push onto new values
					$new_values[ $name ] = $old_value;
					// push onto api names
					$api_names[] = $api_name;
				}
			}
		}
		
		// do we have new values?
		if ( count( $new_values ) ) {
			// yep, update theme mods
			$registry->set_theme_mods( $new_values );
		}

		// do we have old api names?
		if ( count( $api_names ) ) {
			// yes, loop all
			foreach( $api_names as $api_name ) {
				// remove it from settings api
				delete_option( $api_name );
			}
		}

		// all done
		return true;

		/* @var $registry ICE_Option_Registry */
		/* @var $option ICE_Option */
	}
}
