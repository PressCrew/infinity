<?php
/**
 * PIE API: option extensions, domain mapping plugin class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/text' );

/**
 * Domain mapping plugin option
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Plugins_Domain_Mapping
	extends Pie_Easy_Exts_Options_Text
{
	public function init()
	{
		// is the plugin active?
		if ( $this->plugin_active() ) {
			// yes, ok to init
			parent::init();
		} else {
			// not active
			throw new Exception( 'The domain mapping plugin has not been enabled' );
		}
	}

	public function configure( $conf_map, $theme )
	{
		if ( !$conf_map->title ) {
			$conf_map->title = __( 'Custom Domain', pie_easy_text_domain );
		}

		if ( !$conf_map->description ) {
			$conf_map->description = __( 'Enter the custom domain name you wish to map to your site.', pie_easy_text_domain );
		}

		parent::configure( $conf_map, $theme );
	}

	protected function get_option()
	{
		global $wpdb;

		// the statement
		$statement =
			$wpdb->prepare("
				SELECT
					`domain`
				FROM
					`{$wpdb->dmtable}`
				WHERE
					`blog_id` = %d",
				get_current_blog_id()
			);

		return $wpdb->get_var( $statement );
	}

	protected function update_option( $value )
	{
		global $wpdb;

		// is the domain valid?
		if ( $this->validate_domain( $value ) ) {
			// is a domain set already?
			if ( $this->get_option() ) {
				// yes, update statement
				$statement =
					$wpdb->prepare("
						UPDATE
							`{$wpdb->dmtable}`
						SET
							`domain` = %s,
							`active` = 1
						WHERE
							`blog_id` = %d",
						$value,
						get_current_blog_id()
					);
			} else {
				// no, insert statement
				$statement =
					$wpdb->prepare("
						INSERT INTO
							`{$wpdb->dmtable}` ( `blog_id`, `domain`, `active` )
						VALUES
							( %d, %s, 1 )",
						get_current_blog_id(),
						$value
					);
			}

			// execute query and return results
			return $wpdb->query( $statement );
		}

		// something went wrong
		return false;
	}

	public function delete_option()
	{
		global $wpdb;

		// the statement
		$statement =
			$wpdb->prepare("
				DELETE FROM
					`{$wpdb->dmtable}`
				WHERE
					`blog_id` = %d",
				get_current_blog_id()
			);

		// execute query and return result
		return $wpdb->query( $statement );
	}

	/**
	 * Returns true if the domain mapping plugin is active
	 * 
	 * @return boolean 
	 */
	protected function plugin_active()
	{
		return function_exists( 'dm_sunrise_warning' );
	}

	/**
	 * Validate domain to ensure it is of the correct format
	 *
	 * @param string $domain
	 * @return boolean
	 */
	protected function validate_domain( $domain )
	{
		return ( preg_match( '/^[a-z0-9](-?[a-z0-9]+)*\.[a-z]{2,4}$/i', $domain ) );
	}
}

?>
