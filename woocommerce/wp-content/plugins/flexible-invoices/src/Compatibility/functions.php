<?php
/**
 * Need this file for backward compatibility.
 */

if( ! function_exists( 'wpdesk_is_plugin_active' ) ) {

	/**
	 * @param $plugin
	 *
	 * @return bool
	 */
	function wpdesk_is_plugin_active( $plugin ) {
		if ( function_exists( 'is_plugin_active_for_network' ) ) {
			if ( is_plugin_active_for_network( $plugin ) ) {
				return true;
			}
		}

		return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
	}

}
