<?php
/**
 * ICE API: widgets helper class file
 *
 * @author Boone Gorges <boone@gorg.es>
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2012-2015 CUNY Academic Commons, Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage utils
 * @since 1.2
 */

/**
 * Utility class for dealing with sidebar widgets.
 */
class ICE_Widget_Setter
{
	/**
	 * Set a new widget instance for a sidebar programmatically.
	 *
	 * @global array $wp_registered_sidebars
	 * @global array $wp_registered_widget_updates
	 *
	 * @param string $sidebar_id The sidebar id to which the new widget instance should be added.
	 * @param string $widget_id_base The registered widget type (id_base) to use for the new widget instance.
	 * @param array $settings The settings to pass through to the new widget instance.
	 *
	 * @return \WP_Error|bool
	 */
	public static function set_widget( $sidebar_id, $widget_id_base, $settings )
	{
		global $wp_registered_sidebars, $wp_registered_widget_updates;

		// Don't try to set a widget if it hasn't been registered
		if ( ! self::widget_exists( $widget_id_base ) ) {
			return new WP_Error( 'widget_does_not_exist', 'Widget does not exist' );
		}

		if ( ! isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
			return new WP_Error( 'sidebar_does_not_exist', 'Sidebar does not exist' );
		}

		$sidebars = wp_get_sidebars_widgets();
		$sidebar = (array) $sidebars[ $sidebar_id ];

		// Multi-widgets can only be detected by looking at their settings
		$option_name  = 'widget_' . $widget_id_base;

		// Don't let it get pulled from the cache
		wp_cache_delete( $option_name, 'options' );
		$all_settings = get_option( $option_name );

		if ( is_array( $all_settings ) ) {
			$skeys = array_keys( $all_settings );

			// Find the highest numeric key
			rsort( $skeys );

			foreach ( $skeys as $k ) {
				if ( is_numeric( $k ) ) {
					$multi_number = $k + 1;
					break;
				}
			}

			if ( ! isset( $multi_number ) ) {
				$multi_number = 1;
			}

			$all_settings[ $multi_number ] = $settings;

		} else {
			$multi_number = 1;
			$all_settings = array( $multi_number => $settings );
		}

		$widget_id = $widget_id_base . '-' . $multi_number;
		$sidebar[] = $widget_id;

		// Because of the way WP_Widget::update_callback() works, gotta fake the $_POST
		$_POST['widget-' . $widget_id_base] = $all_settings;

		foreach ( (array) $wp_registered_widget_updates as $name => $control ) {

			if ( $name == $widget_id_base ) {

				if ( false === is_callable( $control['callback'] ) ) {
					continue;
				}

				ob_start();
					call_user_func_array( $control['callback'], $control['params'] );
				ob_end_clean();

				break;
			}
		}

		$sidebars[ $sidebar_id ] = $sidebar;
		wp_set_sidebars_widgets( $sidebars );

		return update_option( $option_name, $all_settings );
	}

	/**
	 * Returns true if a sidebar is already populated.
	 *
	 * @param string $sidebar_id The id of the sidebar to check.
	 * @return bool
	 */
	public static function is_sidebar_populated( $sidebar_id )
	{
		$sidebars = wp_get_sidebars_widgets();
		return ( false ===  empty( $sidebars[ $sidebar_id ] ) );
	}

	/**
	 * Moves all active widgets from a given sidebar into the inactive array.
	 *
	 * @param string $sidebar_id
	 * @param string $delete_to
	 * @return \WP_Error|void
	 */
	public static function clear_sidebar( $sidebar_id, $delete_to = 'inactive' )
	{
		$sidebars = wp_get_sidebars_widgets();
		if ( ! isset( $sidebars[ $sidebar_id ] ) ) {
			return new WP_Error( 'sidebar_does_not_exist', 'Sidebar does not exist' );
		}

		if ( 'inactive' == $delete_to ) {
			$sidebars['wp_inactive_widgets'] = array_unique( array_merge( $sidebars['wp_inactive_widgets'], $sidebars[ $sidebar_id ] ) );
		}

		$sidebars[ $sidebar_id ] = array();
		wp_set_sidebars_widgets( $sidebars );
	}

	/**
	 * Returns true if a widget with the given id_base is currently registered.
	 *
	 * @global WP_Widget_Factory $wp_widget_factory
	 * @param string $id_base
	 * @return bool
	 */
	public static function widget_exists( $id_base )
	{
		global $wp_widget_factory;

		foreach ( $wp_widget_factory->widgets as $w ) {
			if ( $id_base == $w->id_base ) {
				return true;
			}
		}

		return false;
	}
}
