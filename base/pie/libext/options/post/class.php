<?php
/**
 * PIE API: option extensions, post class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/select' );

/**
 * Post option
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Post
	extends Pie_Easy_Exts_Options_Select
		implements Pie_Easy_Options_Option_Auto_Field
{

	public function load_field_options()
	{
		// get all posts
		$posts = get_posts();

		// field options
		$options = array();

		// build of options array
		foreach ( $posts as $post ) {
			$options[$post->ID] = apply_filters( 'the_title', $post->post_title, $post->ID );
		}

		return $options;
	}
}

?>
