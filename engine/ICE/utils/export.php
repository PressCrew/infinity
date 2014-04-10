<?php
/**
 * ICE API: exports helper class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage utils
 * @since 1.0
 */

/**
 * Make exporting dynamic data easy
 *
 * @package ICE
 * @subpackage utils
 * @uses ICE_Export_Exception
 */
class ICE_Export extends ICE_Base
{
	/**
	 * File name delimiter
	 */
	const FILE_NAME_DELIM = '-';

	/**
	 * File extension delimiter
	 */
	const FILE_EXT_DELIM = '.';

	/**
	 * Name of the file (without extension)
	 * 
	 * @var string 
	 */
	private $name;

	/**
	 * The file extension
	 *
	 * @var string
	 */
	private $ext;

	/**
	 * Stack of objects to retrieve export data from
	 *
	 * @var array
	 */
	private $stack = array();

	/**
	 * Callback from which to retrieve data
	 *
	 * @var string|array
	 */
	private $callback;

	/**
	 * Map of child instances of self
	 *
	 * @var array
	 */
	private $children = array();

	/**
	 * Constructor
	 *
	 * @param string $name File name (without extension)
	 * @param string $ext File extension (without the "dot")
	 * @param string|array Provide a valid callback which generates the export instead of using the exportable objects stack
	 */
	public function __construct( $name, $ext, $callback = null )
	{
		throw new Exception( 'This class is currently being re-purposed, stay tuned.' );

		$this->name = $name;
		$this->ext = $ext;
		$this->callback = $callback;
	}

	/**
	 */
	public function get_property( $name )
	{
		switch( $name ) {
			case 'name':
			case 'ext':
				return $this->$name;
			default:
				return parent::get_property( $name );
		}
	}

	/**
	 * Push an exportable object onto the stack
	 *
	 * @param ICE_Exportable $obj
	 * @return array
	 */
	public function push( ICE_Exportable $obj )
	{
		// not allowed if callback is set
		if ( $this->callback ) {
			throw new Exception( 'Adding exportable objects not allowed when a callback is set' );
		}

		// push object on to stack
		$this->stack[] = $obj;

		// handle recursive objects
		if ( $obj instanceof ICE_Recursable ) {
			// loop children
			foreach ( $obj->get_children() as $child ) {
				// push child
				$this->push( $child );
			}
		}

		return $this;
	}

	/**
	 * Fetch the export data
	 *
	 * @return string
	 */
	public function fetch()
	{
		// result is null by default
		$data = '';

		// have a callback
		if ( $this->callback ) {
			// have a callback, make sure its callable
			if ( is_callable( $this->callback ) ) {
				// execute it
				$data .= call_user_func( $this->callback );
			}
		} else {
			// loop stack and append result of export method of each
			foreach ( $this->stack as $exportable ) {
				/* @var $exportable ICE_Exportable */
				$data .= $exportable->export();
			}
		}

		// now try to fetch my children
		if ( count( $this->children ) ) {
			// loop all and call update
			foreach( $this->children as $child ) {
				$data .= $child->fetch();
			}
		}

		// return export string
		return $data;
	}

	/**
	 * Returns true if given timestamp plus stale seconds is less than current time.
	 *
	 * @param integer $timestamp Unix timestamp
	 * @param intenger $stale_seconds Number of seconds after which stale is true
	 * @return boolean
	 */
	public function stale( $timestamp, $stale_seconds )
	{
		// must be a number
		if ( is_numeric( $timestamp ) && is_numeric( $stale_seconds ) ) {
			// is current time greater than timestamp + stale seconds?
			if ( time() > $timestamp + $stale_seconds ) {
				// yes, its stale
				return true;
			}
		}

		// not stale
		return false;
	}

	/**
	 * If given timestamp plus stale seconds is less than current time, update the data.
	 *
	 * @param integer $timestamp Unix timestamp
	 * @param integer $stale_seconds Number of seconds after which data is considered stale
	 * @return boolean
	 */
	public function refresh( $timestamp, $stale_seconds )
	{
		// is data stale?
		if ( $this->stale( $timestamp, $stale_seconds ) ) {
			// yes, update it
			return $this->update();
		}
		
		// did NOT refresh
		return false;
	}
}

/**
 * ICE Export Exception
 *
 * @package ICE
 * @subpackage utils
 */
final class ICE_Export_Exception extends Exception {}
