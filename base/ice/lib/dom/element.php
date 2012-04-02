<?php
/**
 * ICE API: base element abstract class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage dom
 * @since 1.0
 */

/**
 * Make DOM elements for components easy
 *
 * @package ICE
 * @subpackage dom
 */
abstract class ICE_Element extends ICE_Base
{
	/**
	 * The API slug to embed in system attribute names
	 * @var string 
	 */
	private $slug = 'system';

	/**
	 * Class offset at which to begin applying suffixes
	 * 
	 * @var integer
	 */
	private $class_suffix_offset = 0;
	
	/**
	 * The HTML id attribute
	 * @var string
	 */
	private $id;

	/**
	 * The HTML system id attribute
	 * @var string
	 */
	private $sid;

	/**
	 * The class names map
	 * @var ICE_Map
	 */
	private $classes;

	/**
	 * Lock toggle
	 * @var boolean
	 */
	private $locked = false;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->classes = new ICE_Map_Lockable();
	}

	/**
	 * Lock this element from further modification
	 *
	 * @return ICE_Element
	 */
	public function lock()
	{
		$this->classes->lock();
		$this->locked = true;

		return $this;
	}

	/**
	 * Return lock status
	 *
	 * @return boolean
	 */
	public function locked()
	{
		return $this->locked;
	}

	/**
	 * Set the API slug
	 *
	 * @param string $slug
	 * @return ICE_Element
	 */
	final public function set_slug( $slug )
	{
		$this->slug = $slug;
		return $this;
	}

	/**
	 * Set the class suffix offset
	 *
	 * @param integer $int
	 * @return ICE_Element
	 */
	final public function set_class_suffix_offset( $int )
	{
		$this->class_suffix_offset = $int;
		return $this;
	}
	
	/**
	 * Set the HTML id attribute value
	 *
	 * @param type $id
	 * @return ICE_Element
	 */
	final public function set_id( $id )
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * Set the HTML system id attribute value
	 *
	 * @param string $sid
	 * @return ICE_Element
	 */
	final public function set_sid( $sid )
	{
		$this->sid = $sid;
		return $this;
	}

	/**
	 * Add a class to the class list
	 *
	 * @param string $class
	 * @return ICE_Element
	 */
	public function add_class( $class )
	{
		if ( is_array( $class ) ) {
			$class = implode( '-', $class );
		}

		$this->classes->add( null, $class );

		return $this;
	}

	/**
	 * Return all classes an an array
	 *
	 * @param string $suffixes,...
	 * @return array
	 */
	protected function get_classes( $suffixes = array() )
	{
		if ( $this->classes->locked() == false ) {
			throw new Exception( 'Getting classes before locking is not allowed' );
		}

		// any suffixes?
		if ( count( $suffixes ) ) {
			// loop all classes
			foreach( $this->classes as $key => $class ) {
				// reached suffix offset?
				if ( $key >= $this->class_suffix_offset ) {
					// yep, suffix on this one
					$classes[] = $class . $this->suffix( $suffixes );
				} else {
					// nope, no suffix
					$classes[] = $class;
				}
			}
			// return suffixed classes
			return $classes;
		} else {
			// return classes as is
			return $this->classes->to_array();
		}
	}

	/**
	 * Generate a suffix given an array of strings
	 *
	 * Nested arrays are supported
	 *
	 * @param array $parts
	 * @param string $glue
	 * @return string
	 */
	protected function suffix( $parts, $glue = '-' )
	{
		$suffix = '';
		
		if ( is_array( $parts ) && count( $parts ) ) {
			foreach ( $parts as $part ) {
				if ( is_array( $part ) ) {
					$suffix .= $this->suffix( $part, $glue );
				} else {
					$suffix .= $glue . $part;
				}
			}
		}
		
		return $suffix;
	}

	/**
	 * Return DOM HTML element id
	 *
	 * @param string $suffix,...
	 * @return string
	 */
	final public function id()
	{
		if ( $this->id ) {
			return $this->id . $this->suffix( $suffixes = func_get_args() );
		}
	}

	/**
	 * Return DOM HTML id selector string
	 *
	 * @param string $suffix,...
	 * @return string
	 */
	final public function id_selector()
	{
		$id = $this->id( $suffixes = func_get_args() );
		
		if ( $id ) {
			return '#' . $id;
		}
	}

	/**
	 * Return DOM HTML identifier attribute
	 *
	 * @param string $suffix,...
	 * @return string
	 */
	final public function id_attribute()
	{
		$id = $this->id( $suffixes = func_get_args() );

		if ( $id ) {
			return sprintf( 'id="%s"', esc_attr( $id ) );
		}
	}

	/**
	 * Return DOM HTML element special id
	 *
	 * @param string $suffix,...
	 * @return string
	 */
	final public function sid()
	{
		if ( $this->sid ) {
			return $this->sid . $this->suffix( $args = func_get_args() );
		}
	}

	/**
	 * Return DOM HTML special id selector string
	 *
	 * @param string $suffix,...
	 * @return string
	 */
	public function sid_selector()
	{
		$sid = $this->sid( $suffixes = func_get_args() );

		if ( $sid ) {
			return sprintf( '[data-%s-sid="%s"]', $this->slug, esc_attr( $sid ) );
		}
	}

	/**
	 * Return DOM HTML identifier attribute
	 *
	 * @param string $suffix,...
	 * @return string
	 */
	final public function sid_attribute()
	{
		$sid = $this->sid( $suffixes = func_get_args() );

		if ( $sid ) {
			return sprintf( 'data-%s-sid="%s"', $this->slug, esc_attr( $sid ) );
		}
	}

	/**
	 * Return DOM HTML class list separated by spaces
	 *
	 * @param string $addtl,... zero or more additional classes to append
	 * @return string
	 */
	public function class_list()
	{
		$classes = $this->get_classes();

		if ( $classes ) {
			return implode( ' ', $classes ) . $this->suffix( $addtl = func_get_args(), ' ' );
		}
	}

	/**
	 * Return DOM HTML class names separated by spaces and optional suffixed
	 *
	 * @param string $suffix,...
	 * @return string
	 */
	public function class_names()
	{
		$classes = $this->get_classes( $suffixes = func_get_args() );

		if ( $classes ) {
			return implode( ' ', $classes );
		}
	}

	/**
	 * Return DOM HTML class selector string
	 *
	 * @param string $suffix,...
	 * @return string
	 */
	public function class_selector()
	{
		$classes = $this->get_classes( $suffixes = func_get_args() );

		if ( count( $classes ) ) {
			return '.' . implode( '.', $classes );
		}
	}

	/**
	 * Return DOM HTML class attribute
	 *
	 * @param string $suffix,...
	 * @return string
	 */
	final public function class_attribute()
	{
		$classes = $this->class_names( $suffixes = func_get_args() );

		if ( $classes ) {
			return sprintf( 'class="%s"', esc_attr( $classes ) );
		}
	}

}

?>
