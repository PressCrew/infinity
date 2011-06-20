<?php
/**
 * Infinity Theme: sections classes file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package api
 * @subpackage sections
 * @since 1.0
 */

Pie_Easy_Loader::load( 'sections' );

/**
 * Infinity Theme: sections policy
 *
 * @package api
 * @subpackage sections
 */
class Infinity_Sections_Policy extends Pie_Easy_Sections_Policy
{
	/**
	 * @return Pie_Easy_Sections_Policy
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
	 * @return Infinity_Sections_Registry
	 */
	final public function new_registry()
	{
		return new Infinity_Sections_Registry();
	}

	/**
	 * @return Infinity_Exts_Section_Factory
	 */
	final public function new_factory()
	{
		return new Infinity_Exts_Section_Factory();
	}

	/**
	 * @return Infinity_Sections_Renderer
	 */
	final public function new_renderer()
	{
		return new Infinity_Sections_Renderer();
	}

	/**
	 * @param string $ext
	 * @return Pie_Easy_Sections_Section
	 */
	final public function load_ext( $ext )
	{
		return infinity_load_extension( $this->get_handle(), $ext );
	}
}

/**
 * Infinity Theme: sections registry
 *
 * @package api
 * @subpackage sections
 */
class Infinity_Sections_Registry extends Pie_Easy_Sections_Registry
{
	// nothing custom yet
}

/**
 * Infinity Theme: section factory
 *
 * @package api
 * @subpackage exts
 */
class Infinity_Exts_Section_Factory extends Pie_Easy_Sections_Factory
{
	// nothing custom yet
}

/**
 * Infinity Theme: sections renderer
 *
 * @package api
 * @subpackage sections
 */
class Infinity_Sections_Renderer extends Pie_Easy_Sections_Renderer
{
	/**
	 * Render the section layout around the section's content
	 *
	 * @param string $content The content that should be wrapped in the section layout
	 */
	protected function render_section( $content )
	{ ?>
		<div class="<?php $this->render_classes() ?>">
			<?php print $content ?>
		</div><?php
	}
}

?>
