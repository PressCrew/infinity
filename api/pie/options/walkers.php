<?php
/**
 * PIE API: options walkers class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options
 * @since 1.0
 */

/**
 * Make category options easy
 *
 * @package PIE
 * @subpackage options
 */
class Pie_Easy_Options_Walker_Category extends Walker_Category
{
	/**
	 * @see Walker_Category::start_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $category Category data object.
	 * @param int $depth Depth of category in reference to parents.
	 * @param array $args
	 */
	function start_el( &$output, $category, $depth, $args )
	{
		// get option from args
		$option = $args['pie_easy_option'];
		
		// selected values
		$selected = $option->get();

		// handle empty values
		if ( empty( $selected ) ) {
			$selected = array();
		}

		// put a checkbox before the category
		$output .= sprintf(
			'<input type="checkbox" value="%s" name="%s[]" class="%s"%s /><label>%s</label>',
			esc_attr( $category->term_id ),
			esc_attr( $option->name ),
			esc_attr( $option->field_class ),
			( in_array( $category->term_id, $selected ) ) ? ' checked="checked"' : null,
			apply_filters( 'list_cats', esc_attr( $category->name ), $category )
		);
	}

	/**
	 * @see Walker::end_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $page Not used.
	 * @param int $depth Depth of category. Not used.
	 * @param array $args Only uses 'list' for whether should append to output.
	 */
	function end_el(&$output, $page, $depth, $args) {
		$output .= PHP_EOL;
	}
}

/**
 * Make page options easy
 *
 * @package PIE
 * @subpackage options
 */
class Pie_Easy_Options_Walker_Page extends Walker_Page
{
	/**
	 * @see Walker::start_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $page Page data object.
	 * @param int $depth Depth of page. Used for padding.
	 * @param int $current_page Page ID.
	 * @param array $args
	 */
	function start_el(&$output, $page, $depth, $args, $current_page)
	{
		// get option from args
		$option = $args['pie_easy_option'];

		// selected values
		$selected = $option->get();

		// handle empty values
		if ( empty( $selected ) ) {
			$selected = array();
		}

		// put a checkbox before the page
		$output .= sprintf(
			'<input type="checkbox" value="%s" name="%s[]" class="%s"%s /><label>%s</label>',
			esc_attr( $page->ID ),
			esc_attr( $option->name ),
			esc_attr( $option->field_class ),
			( in_array( $page->ID, $selected ) ) ? ' checked="checked"' : null,
			apply_filters( 'the_title', $page->post_title, $page->ID )
		);
	}

	/**
	 * @see Walker::end_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $page Page data object. Not used.
	 * @param int $depth Depth of page. Not Used.
	 */
	function end_el(&$output, $page, $depth) {
		$output .= PHP_EOL;
	}
}

?>
