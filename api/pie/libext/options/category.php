<?php
/**
 * PIE API: option extensions, category class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options-ext
 * @since 1.0
 */

/**
 * Category option
 *
 * @package PIE
 * @subpackage options-ext
 */
class Pie_Easy_Exts_Option_Category
	extends Pie_Easy_Options_Option
{
	/**
	 * Render a category select tag
	 */
	public function render_field()
	{
		$args = array(
			'show_option_all'    => null,
			'show_option_none'   => null,
			'orderby'            => 'ID',
			'order'              => 'ASC',
			'show_last_update'   => false,
			'show_count'         => false,
			'hide_empty'         => false,
			'child_of'           => false,
			'exclude'            => null,
			'echo'               => true,
			'selected'           => $this->get(),
			'hierarchical'       => false,
			'name'               => $this->name,
			'id'                 => $this->field_id,
			'class'              => $this->field_class,
			'depth'              => false,
			'tab_index'          => false,
			'taxonomy'           => 'category',
			'hide_if_empty'      => false );

		// use WordPress function
		wp_dropdown_categories( $args );
	}
}

?>
