<?php
/**
 * ICE API: compat helpers class file.
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2014 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage utils
 * @since 1.0
 */

/**
 * Backwards compatibility helpers for the posts.
 *
 * @package ICE
 * @subpackage utils
 */
class ICE_Compat_Posts
{
	/**
	 * Rename every "old" postmeta key with the given new key.
	 *
	 * Prefixing of the new key is handled automatically, but can be overridden with a 3rd argument.
	 *
	 * @global wpdb $wpdb
	 * @param string $old_key
	 * @param string $new_key
	 * @param string $new_key_prefix
	 * @return int|false The number of keys renamed, or false on error.
	 */
	static public function rename_postmeta_key( $old_key, $new_key, $new_key_prefix = INFINITY_META_KEY_PREFIX )
	{
		global $wpdb;

		// update every row matching old key with new key
		return
			$wpdb->update(
				$wpdb->postmeta,
				array( 'meta_key' => $new_key_prefix . $new_key ),
				array( 'meta_key' => $old_key )
			);
	}
}

/**
 * Backwards compatibility helpers for the options component.
 *
 * @package ICE
 * @subpackage utils
 */
class ICE_Compat_Option
{
	/**
	 * Return the old option "api name".
	 *
	 * @param string $option_name The old option name.
	 * @return string
	 */
	static public function get_api_name( $option_name )
	{
		// pre 1.2 alpha aname format
		$aname = 'option/' . $option_name;

		// return old api name
		return implode(
			'.',
			array(
				ICE_Option::API_PREFIX,
				hash( 'crc32', $aname ),
				ICE_ACTIVE_THEME
			)
		);
	}
}
