<?php
/**
 * PIE API: file system file stat class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage utils
 * @since 1.0
 */

/**
 * File stat info
 *
 * @package PIE
 * @subpackage utils
 */
final class Pie_Easy_File extends Pie_Easy_Base
{
	/**
	 * File path
	 *
	 * @var string
	 */
	protected $f;

	/**
	 * File path exists
	 *
	 * @var boolean
	 */
	protected $e = false;

	/**
	 * File path is readable
	 *
	 * @var boolean
	 */
	protected $r = false;

	/**
	 * File path is writable
	 *
	 * @var boolean
	 */
	protected $w = false;

	/**
	 * Constructor
	 *
	 * @param string $filename Absolute path to file
	 */
	public function __construct( $filename )
	{
		$this->f = $filename;
		$this->e = file_exists( $filename );

		if ( $this->e ) {
			$this->r = is_readable( $filename );
			$this->w = is_writable( $filename );
		}
	}

	/**
	 * Return file name path
	 *
	 * @return string
	 */
	public function filename()
	{
		return $this->f;
	}

	/**
	 * Returns true if file exists
	 *
	 * @return boolean
	 */
	public function exists()
	{
		return $this->e;
	}

	/**
	 * Returns true if file is readable
	 *
	 * @return boolean
	 */
	public function is_readable()
	{
		return $this->r;
	}

	/**
	 * Returns true if file is writable
	 *
	 * @return boolean
	 */
	public function is_writable()
	{
		return $this->w;
	}
}

?>