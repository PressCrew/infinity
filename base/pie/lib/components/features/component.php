<?php
/**
 * PIE API: feature class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-components
 * @subpackage features
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/component', 'ui/styleable', 'utils/files' );

/**
 * Make a feature easy
 *
 * @package PIE-components
 * @subpackage features
 */
abstract class Pie_Easy_Features_Feature
	extends Pie_Easy_Component
{
	/**
	 * Check if theme supports this feature
	 *
	 * @return boolean
	 */
	public function supported()
	{
		if ( !current_theme_supports( $this->name ) ) {
			return false;
		}

		return parent::supported();
	}
	
}

?>
