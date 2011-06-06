<?php
/**
 * Infinity Theme: shortcodes classes file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package api
 * @subpackage shortcodes
 * @since 1.0
 */

Pie_Easy_Loader::load( 'shortcodes' );

/**
 * Infinity Theme: shortcodes policy
 *
 * @package api
 * @subpackage shortcodes
 */
class Infinity_Shortcodes_Policy extends Pie_Easy_Shortcodes_Policy
{
	/**
	 * @return Pie_Easy_Shortcodes_Policy
	 */
	static public function instance()
	{
		self::$calling_class = __CLASS__;
		return parent::instance();
	}
	
	/**
	 * Return the name of the implementing API
	 *
	 * @return string
	 */
	final public function get_api_slug()
	{
		return 'infinity_theme';
	}

	/**
	 * @return Infinity_Shortcodes_Registry
	 */
	final public function new_registry()
	{
		return new Infinity_Shortcodes_Registry();
	}

	/**
	 * @return Infinity_Exts_Shortcode_Factory
	 */
	final public function new_factory()
	{
		return new Infinity_Exts_Shortcode_Factory();
	}

	/**
	 * @return Infinity_Shortcodes_Renderer
	 */
	final public function new_renderer()
	{
		return new Infinity_Shortcodes_Renderer();
	}

}

/**
 * Infinity Theme: shortcodes registry
 *
 * @package api
 * @subpackage shortcodes
 */
class Infinity_Shortcodes_Registry extends Pie_Easy_Shortcodes_Registry
{
	// nothing custom yet
}

/**
 * Infinity Theme: section factory
 *
 * @package api
 * @subpackage exts
 */
class Infinity_Exts_Shortcode_Factory extends Pie_Easy_Shortcodes_Factory
{
	// nothing custom yet
}

/**
 * Infinity Theme: shortcodes renderer
 *
 * @package api
 * @subpackage shortcodes
 */
class Infinity_Shortcodes_Renderer extends Pie_Easy_Shortcodes_Renderer
{
	// nothing custom yet
}

?>
