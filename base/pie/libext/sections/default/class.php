<?php
/**
 * PIE API: section extensions, default section class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage sections
 * @since 1.0
 */

/**
 * Default section
 *
 * @package PIE-extensions
 * @subpackage sections
 */
class Pie_Easy_Exts_Sections_Default
	extends Pie_Easy_Sections_Section
{
	/**
	 */
	public function init_styles()
	{
		parent::init_styles();

		// slurp admin styles
		$this->style()
			->section( 'admin' )
			->cache( 'admin', 'admin.css' );
	}
}

?>
