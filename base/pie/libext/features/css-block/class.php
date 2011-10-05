<?php
/**
 * PIE API: feature extensions, css block styles feature class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage features
 * @since 1.0
 */

/**
 * CSS Block styling feature
 *
 * @package PIE-extensions
 * @subpackage features
 */
class Pie_Easy_Exts_Features_Css_Block
	extends Pie_Easy_Features_Feature
{
	/**
	 */
	public function configure( $conf_map, $theme )
	{
		// RUN PARENT FIRST!
		parent::configure( $conf_map, $theme );

		// css title class
		if ( isset( $conf_map['css_selector'] ) ) {
			$this->directives()->set( $theme, 'css_selector', $conf_map['css_selector'] );
		}
	}
	
	/**
	 */
	final public function export_css()
	{
		// get a rule
		$rule = $this->style()->new_rule( $this->css_selector );

		// height
		if ( $this->has_suboption('height') ) {
			$rule->ad( 'height', $this->get_suboption('height')->get() . 'px' );
		}

		// border width
		if ( $this->has_suboption('border-width') ) {
			$rule->ad( 'border-width', $this->get_suboption('border-width')->get() . 'px' );
		}

		// border color
		if ( $this->has_suboption('border-color') ) {
			$rule->ad( 'border-color', $this->get_suboption('border-color')->get() );
		}

		return parent::export_css();
	}
}

?>
