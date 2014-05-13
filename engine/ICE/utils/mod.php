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
			$this->mods = get_theme_mod( $this->name );
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