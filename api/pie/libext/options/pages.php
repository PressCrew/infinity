<?php
/**
 * PIE API: option extensions, pages class file
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
 * Pages option
 *
 * @package PIE
 * @subpackage options-ext
 */
class Pie_Easy_Exts_Option_Pages
	extends Pie_Easy_Options_Option
{
	/**
	 * Render page checkboxes
	 */
	public function render_field()
	{
		$args = array(
			'depth'        => 0,
			'show_date'    => '',
			'date_format'  => get_option('date_format'),
			'child_of'     => 0,
			'exclude'      => '',
			'include'      => '',
			'title_li'     => '',
			'echo'         => true,
			'authors'      => '',
			'sort_column'  => 'menu_order, post_title',
			'link_before'  => '',
			'link_after'   => '',
			'walker'			=> new Pie_Easy_Options_Walker_Page(),
			'pie_easy_option'	=> $this );

		// render div wrapper if applicable
		if ( $this->field_id ) { ?>
			<div id="<?php print $this->field_id ?>"><?php
		}

		// call the WordPress function
		wp_list_pages( $args );

		// close div wrapper if applicable
		if ( $this->field_id ) { ?>
			</div><?php
		}
	}
}

?>
