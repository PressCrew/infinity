<?php
/**
 * PIE API: map collection iterator class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage collections
 * @since 1.0
 */

/**
 * Map collection iterator
 *
 * This is a fork of the Yii CMapIterator implementation
 * 
 * Originally authored by Qiang Xue for the {@link http://www.yiiframework.com/ Yii Framework},
 * Copyright 2008-2010 {@link http://www.yiiframework.com/ Yii Software LLC},
 * and released under the {@link http://www.yiiframework.com/license/ Yii License}
 *
 * @package PIE
 * @subpackage collections
 */
class Pie_Easy_Map_Iterator
	extends Pie_Easy_Base
		implements Iterator
{
	/**
	 * @var array The data to be iterated through
	 */
	private $data;

	/**
	 * @var array List of keys in the map
	 */
	private $keys;

	/**
	 * @var mixed Current key
	 */
	private $key;

	/**
	 * @param array $data The data to be iterated through
	 */
	public function __construct( &$data )
	{
		$this->data = &$data;
		$this->keys = array_keys( $data );
	}

	/**
	 * Rewinds internal array pointer.
	 */
	public function rewind()
	{
		$this->key = reset( $this->keys );
	}

	/**
	 * Returns the key of the current array element.
	 *
	 * @return mixed
	 */
	public function key()
	{
		return $this->key;
	}

	/**
	 * Returns the current array element.
	 *
	 * @return mixed
	 */
	public function current()
	{
		return $this->data[$this->key];
	}

	/**
	 * Moves the internal pointer to the next array element.
	 */
	public function next()
	{
		$this->key = next( $this->keys );
	}

	/**
	 * Returns whether there is an element at current position.
	 *
	 * @return boolean
	 */
	public function valid()
	{
		return $this->key !== false;
	}
}

?>
