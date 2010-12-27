<?php
/**
 * PIE API stack collection class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage collections
 * @since 1.0
 */

/**
 * Make a stack collection easy
 *
 * This is a fork of the Yii CStack implementation
 *
 * Originally authored by Qiang Xue for the {@link http://www.yiiframework.com/ Yii Framework},
 * Copyright 2008-2010 {@link http://www.yiiframework.com/ Yii Software LLC},
 * and released under the {@link http://www.yiiframework.com/license/ Yii License}
 */
class Pie_Easy_Stack implements IteratorAggregate,Countable
{
	/**
	 * Internal data storage
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Number of items
	 *
	 * @var integer
	 */
	private $count = 0;

	/**
	 * Constructor.
	 *
	 * Initializes the stack with an array or an iterable object.
	 *
	 * @param array $data The initial data. Default is null, meaning no initialization.
	 * @throws Exception If data is not null and neither an array nor an iterator.
	 */
	public function __construct( $data = null )
	{
		if ( $data !== null ) {
			$this->copy_from( $data );
		}
	}

	/**
	 * Return entire stack as an array
	 *
	 * @return array
	 */
	public function to_array()
	{
		return $this->data;
	}

	/**
	 * Copies iterable data into the stack.
	 *
	 * Existing data in the list will be cleared first.
	 *
	 * @param mixed $data The data to be copied from, must be an array or object implementing Traversable
	 * @throws Exception If data is neither an array nor a Traversable.
	 */
	public function copy_from( $data )
	{
		if( is_array( $data ) || ( $data instanceof Traversable ) ) {
			$this->clear();
			foreach( $data as $item ) {
				$this->data[] = $item;
				++$this->count;
			}
		} elseif ( $data !== null ) {
			throw new Exception( 'Stack data must be an array or an object implementing Traversable.' );
		}
	}

	/**
	 * Removes all items in the stack.
	 */
	public function clear()
	{
		$this->count = 0;
		$this->data = array();
	}

	/**
	 * Check whether the stack contains an item
	 * 
	 * @param mixed The item
	 * @return boolean 
	 */
	public function contains( $item )
	{
		return array_search( $item, $this->data, true ) !== false;
	}

	/**
	 * Returns the item at the top of the stack.
	 * 
	 * Unlike pop() this method does not remove the item from the stack.
	 * 
	 * @return mixed Item at the top of the stack
	 * @throws Exception if the stack is empty
	 */
	public function peek()
	{
		if ( $this->count ) {
			return $this->data[$this->count - 1];
		} else {
			throw new Exception( 'The stack is empty.' );
		}
	}

	/**
	 * Pops up the item at the top of the stack.
	 * 
	 * @return mixed The item at the top of the stack
	 * @throws Exception if the stack is empty
	 */
	public function pop()
	{
		if( $this->count ) {
			--$this->count;
			return array_pop($this->data);
		} else {
			throw new Exception( 'The stack is empty.' );
		}
	}

	/**
	 * Pushes an item into the stack.
	 * 
	 * @param mixed $item The item to be pushed into the stack
	 */
	public function push( $item )
	{
		++$this->count;
		array_push( $this->data, $item );
	}

	/**
	 * The number of items in the stack
	 * 
	 * @return integer 
	 */
	public function count()
	{
		return $this->count;
	}

	/**
	 * Returns an iterator for traversing the items in the stack.
	 *
	 * @return Iterator An iterator for traversing the items in the stack.
	 */
	public function getIterator()
	{
		return new Pie_Easy_Stack_Iterator( $this->data );
	}
}

?>
