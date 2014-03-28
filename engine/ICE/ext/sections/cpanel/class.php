<?php
/**
 * ICE API: section extensions, cpanel section class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage sections
 * @since 1.0
 */

/**
 * Cpanel section
 *
 * @package ICE-extensions
 * @subpackage sections
 */
class ICE_Ext_Section_Cpanel
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
