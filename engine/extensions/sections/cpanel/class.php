<?php
/**
 * Infinity Theme: section extensions, cpanel section class file.
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage extensions
 * @since 1.2
 */

/**
 * Cpanel section.
 *
 * @package Infinity
 * @subpackage extensions
 */
class Infinity_Ext_Section_Cpanel
	extends ICE_Section
{
	/**
	 */
	public function init_admin_styles()
	{
		parent::init_admin_styles();

		// inject admin styles
		$this->style()->inject( 'admin', 'admin.css' );
	}
}
