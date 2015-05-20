<?php
/**
 * ICE API: theme modifications helper class file.
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2014 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage utils
 * @since 1.2
 */

class ICE_Mod extends ICE_Base
{
	/**
	 * The top level key under which to store this set of mods.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The array of mods to save under the top level key (name).
	 *
	 * @var array
	 */
	private $mods = array();

	/**
	 * This is set to true when the mods array is out of sync with
	 * the values in the database after an update() or remove().
	 *
	 * @var boolean
	 */
	private $stale = false;

	/**
	 * Array of top level keys which are currently locked.
	 *
	 * @var array
	 */
	static private $locked = array();

	/**
	 * Constructor.
	 *
	 * @param string $name The top level key to save mods under.
	 * @throws Exception
	 */
	final public function __construct( $name )
	{
		// is name already in use?
		if ( false === isset( self::$locked[ $name ] ) ) {
			// nope, set the name
			$this->name = $name;
			// populate mods
			$this->refresh();
		} else {
			throw new Exception( 'Must destroy all old instances before trying to reload a mod.' );
		}
	}

	/**
	 * Destructor.
	 *
	 * Unlucks top level key when no references to object remain.
	 */
	public function __destruct()
	{
		// unlock it
		unset( self::$locked[ $this->name ] );
	}

	/**
	 * Load a fresh copy of the mods from the database.
	 *
	 * @param boolean $force Set to true to override a stale condition.
	 * @return boolean
	 * @throws Exception
	 */
	public function refresh( $force = false )
	{
		// condition cannot be stale
		if ( true === $force || false === $this->stale ) {
			// get fresh copy of theme mods from database
			$mods = get_theme_mod( $this->name );
			// did we get an array?
			if ( true === is_array( $mods ) ) {
				// yep, use it
				$this->mods = $mods;
			} else {
				// no, force it to empty array
				$this->mods = array();
			}
			// no longer stale
			$this->stale = false;
			// success
			return true;
		} else {
			throw new Exception( 'Modifications were made and save() was not called.' );
		}
	}

	/**
	 * Saves all modifications to database.
	 *
	 * @return boolean
	 */
	public function save()
	{
		// condition must be stale
		if ( true === $this->stale ) {
			// save changes
			set_theme_mod( $this->name, $this->mods );
			// no longer stale
			$this->stale = false;
			// success
			return true;
		}

		// no save occurred
		return false;
	}

	/**
	 * Get the value for key, with optional default.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get( $key, $default = null )
	{
		// is key set?
		if ( isset( $this->mods[ $key ] ) ) {
			// return entire array at key
			return $this->mods[ $key ];
		}

		// not set, return default
		return $default;
	}

	/**
	 * Update the value for given key.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function set( $key, $value )
	{
		// is the value null or an empty string?
		if ( $value === null || $value === '' ) {
			// its pointless to store this option
			// try to delete it in case it already exists
			$this->remove( $key );
		} else {
			// set value for key
			$this->mods[ $key ] = $value;
			// stale condition exists
			$this->stale = true;
		}
	}
	
	/**
	 * Rename the given key.
	 *
	 * @param string $old_key
	 * @param string $new_key
	 * @param bool $silent
	 * @return bool
	 */
	public function rename( $old_key, $new_key, $silent = true )
	{
		// is new key a non-empty string?
		if (
			true === is_string( $new_key ) &&
			false === empty( $new_key )
		) {
			// does new key already exist?
			if ( true === array_key_exists( $new_key, $this->mods ) ) {

				// new key is already set... never overwrite it!
				// return true unless silent mode is toggled off.
				return ( false === $silent ) ? false : true;

			// does old key exist?
			} elseif ( true === array_key_exists( $old_key, $this->mods ) ) {

				// reference value to new key
				$this->mods[ $new_key ] =& $this->mods[ $old_key ];
				// remove old key
				unset( $this->mods[ $old_key ] );
				// stale condition exists
				$this->stale = true;
				// succussful rename
				return true;

			// old key does not exist
			} else {

				// hrmm... maybe it's a deprecated option?
				if ( true === $this->rename_compat( $old_key, $new_key, $silent ) ) {
					// successful rename (or silent mode is on)
					return true;
				} else {
					// all rename methods failed,
					// return true unless silent mode is toggled off.
					return ( false === $silent ) ? false : true;
				}

			}
		}

		// new key is bad, this is a developer level fatal error
		throw new Exception( 'Rename theme modification key failed: new key must be a non-empty string.' );
	}

	/**
	 * Rename the given key using backwards compatible logic.
	 *
	 * Really old options were stored in their own rows in the options table (top level options).
	 *
	 * @param string $old_key
	 * @param string $new_key
	 * @param bool $silent
	 * @return bool
	 */
	final protected function rename_compat( $old_key, $new_key, $silent = true )
	{
		// load compat util
		ICE_Loader::load_lib( 'utils/compat' );
		
		// get deprecated api name
		$api_name = ICE_Compat_Option::get_api_name( $old_key );

		// try to get the old value
		$old_value = get_option( $api_name );

		// get a value? (missing a value is common, don't panic)
		if ( false !== $old_value ) {
			// yes, set the mod
			$this->set( $new_key, $old_value );
			// delete old option
			delete_option( $api_name );
			// successful rename
			return true;
		}

		// no rename attempted
		return ( false === $silent ) ? false : true;
	}

	/**
	 * Unset key, and completely remove from database.
	 *
	 * @param string $key
	 */
	public function remove( $key )
	{
		// remove key completely
		unset( $this->mods[ $key ] );
		// stale condition exists
		$this->stale = true;
	}

}