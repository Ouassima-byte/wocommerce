<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPDesk_Flexible_Invoices_Tracker' ) ) {
	class WPDesk_Flexible_Invoices_Tracker {

		public static $script_version = '11';

		public function hooks() {
			add_filter( 'wpdesk_tracker_data', array( $this, 'wpdesk_tracker_data' ), 11 );
			add_filter( 'wpdesk_tracker_notice_screens', array( $this, 'wpdesk_tracker_notice_screens' ) );
			add_filter( 'wpdesk_track_plugin_deactivation', array( $this, 'wpdesk_track_plugin_deactivation' ) );

			add_filter( 'plugin_action_links_flexible-invoices/flexible-invoices.php', array( $this, 'plugin_action_links' ) );
			add_action( 'activated_plugin', array( $this, 'activated_plugin' ), 10, 2 );
		}

		public function wpdesk_track_plugin_deactivation( $plugins ) {
			$plugins['flexible-invoices/flexible-invoices.php'] = 'flexible-invoices/flexible-invoices.php';
			return $plugins;
		}

		public function wpdesk_tracker_data( $data ) {
			$plugin_data = array(
				'invoices_by_status'        => array(),
				'invoices_by_currency'      => array(),
				'avg_invoices_per_month'    => 0,
			);

			$all_invoices = 0;
			global $wpdb;
			$sql = "
					SELECT count(p.ID) AS count, p.post_status AS post_status
					FROM {$wpdb->posts} p 
					WHERE p.post_type = 'inspire_invoice'
					GROUP BY p.post_status
				";
			$query = $wpdb->get_results( $sql );
			if ( $query ) {
				foreach ( $query as $row ) {
					$plugin_data['invoices_by_status'][$row->post_status] = $row->count;
					$all_invoices = $all_invoices + $row->count;
				}
			}
			$plugin_data['all_invoices'] = $all_invoices;

			$sql = "
					SELECT TIMESTAMPDIFF(MONTH, min(p.post_date), max(p.post_date) )+1 AS months
					FROM {$wpdb->posts} p 
					WHERE p.post_type = 'inspire_invoice'
				";
			$query = $wpdb->get_results( $sql );
			if ( $query ) {
				foreach ( $query as $row ) {
					if ( $row->months != 0 ) {
						$plugin_data['avg_invoices_per_month'] = floatval( $all_invoices )/floatval( $row->months );
					}
				}
			}

			$sql = "
					SELECT count(p.ID) AS count, m.meta_value AS currency
					FROM {$wpdb->posts} p, {$wpdb->postmeta} m
					WHERE p.post_type = 'inspire_invoice'
						AND p.post_status = 'publish'
						AND p.ID = m.post_id
						AND m.meta_key = '_currency'
					GROUP BY m.meta_value
				";
			$query = $wpdb->get_results( $sql );
			if ( $query ) {
				foreach ( $query as $row ) {
					$plugin_data['invoices_by_currency'][$row->currency] = $row->count;
					$all_invoices = $all_invoices + $row->count;
				}
			}

			$plugin_data['show_signatures'] = get_option( 'inspire_invoices_show_signatures', '' );
			$plugin_data['hide_vat'] = get_option( 'inspire_invoices_hide_vat', '' );
			$plugin_data['hide_vat_number'] = get_option( 'inspire_invoices_hide_vat_number', '' );

			$data['flexible_invoices'] = $plugin_data;

			return $data;
		}

		public function wpdesk_tracker_notice_screens( $screens ) {
			$current_screen = get_current_screen();
			if ( $current_screen->parent_file == 'edit.php?post_type=inspire_invoice' ) {
				$screens[] = $current_screen->id;
			}
			return $screens;
		}

		public function plugin_action_links( $links ) {
			if ( !wpdesk_tracker_enabled() || apply_filters( 'wpdesk_tracker_do_not_ask', false ) ) {
				return $links;
			}
			$options = get_option('wpdesk_helper_options', array() );
			if ( !is_array( $options ) ) {
				$options = array();
			}
			if ( empty( $options['wpdesk_tracker_agree'] ) ) {
				$options['wpdesk_tracker_agree'] = '0';
			}
			$plugin_links = array();
			if ( $options['wpdesk_tracker_agree'] == '0' ) {
				$opt_in_link = admin_url( 'admin.php?page=wpdesk_tracker&plugin=flexible-invoices/flexible-invoices.php' );
				$plugin_links[] = '<a href="' . $opt_in_link . '">' . __( 'Opt-in', 'flexible-invoices' ) . '</a>';
			}
			else {
				$opt_in_link = admin_url( 'plugins.php?wpdesk_tracker_opt_out=1&plugin=flexible-invoices/flexible-invoices.php' );
				$plugin_links[] = '<a href="' . $opt_in_link . '">' . __( 'Opt-out', 'flexible-invoices' ) . '</a>';
			}
			return array_merge( $plugin_links, $links );
		}


		public function activated_plugin( $plugin, $network_wide ) {
			if ( $network_wide ) {
				return;
			}
			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				return;
			}
			if ( !wpdesk_tracker_enabled() ) {
				return;
			}
			if ( $plugin == 'flexible-invoices/flexible-invoices.php' ) {
				$options = get_option('wpdesk_helper_options', array() );

				if ( empty( $options ) ) {
					$options = array();
				}
				if ( empty( $options['wpdesk_tracker_agree'] ) ) {
					$options['wpdesk_tracker_agree'] = '0';
				}
				$wpdesk_tracker_skip_plugin = get_option( 'wpdesk_tracker_skip_flexible_invoices', '0' );
				if ( $options['wpdesk_tracker_agree'] == '0' && $wpdesk_tracker_skip_plugin == '0' ) {
					update_option( 'wpdesk_tracker_notice', '1' );
					update_option( 'wpdesk_tracker_skip_flexible_invoices', '1' );
					if ( !apply_filters( 'wpdesk_tracker_do_not_ask', false ) ) {
						wp_redirect( admin_url( 'admin.php?page=wpdesk_tracker&plugin=flexible-invoices/flexible-invoices.php' ) );
						exit;
					}
				}
			}
		}

	}

}

if ( !function_exists( 'wpdesk_activated_plugin_activation_date' ) ) {
	function wpdesk_activated_plugin_activation_date( $plugin, $network_wide ) {
		$option_name = 'plugin_activation_' . $plugin;
		$activation_date = get_option( $option_name, '' );
		if ( $activation_date == '' ) {
			$activation_date = current_time( 'mysql' );
			update_option( $option_name, $activation_date );
		}
	}
	add_action( 'activated_plugin', 'wpdesk_activated_plugin_activation_date', 10, 2 );
}

if ( !function_exists( 'wpdesk_tracker_enabled' ) ) {
	function wpdesk_tracker_enabled() {
		$tracker_enabled = true;
		if ( !empty( $_SERVER['SERVER_ADDR'] ) && $_SERVER['SERVER_ADDR'] == '127.0.0.1' ) {
			$tracker_enabled = false;
		}
		return apply_filters( 'wpdesk_tracker_enabled', $tracker_enabled );
		// add_filter( 'wpdesk_tracker_enabled', '__return_true' );
		// add_filter( 'wpdesk_tracker_do_not_ask', '__return_true' );
	}
}
