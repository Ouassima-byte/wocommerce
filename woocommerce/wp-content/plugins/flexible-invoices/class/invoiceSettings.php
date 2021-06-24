<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class invoiceSettings {

	/** @var string slug od administrator role */
	const ADMIN_ROLE       = 'administrator';
	const SHOPMANAGER_ROLE = 'shop_manager';

	const SETTINGS_PREFIX = 'inspire_invoices';

	private $_plugin;

	public function __construct( $plugin ) {
		$this->_plugin = $plugin;
		add_action( 'admin_init', array( $this, 'update_settings_action' ) );
		add_action( 'admin_menu', array( $this, 'initAdminMenuAction' ) );

		add_action( 'wp_ajax_woocommerce-invoice-generate-report', array( $this, 'generateReportAction' ) );
	}

	public function initAdminMenuAction() {
		add_submenu_page(
			'edit.php?post_type=inspire_invoice',
			__( 'Invoices Settings', 'flexible-invoices' ),
			__( 'Settings', 'flexible-invoices' ),
			'manage_options',
			'invoices_settings',
			array( $this, 'renderInvoicesSettingsPage' )
		);
	}

	/**
	 * wordpress action
	 *
	 * renders invoices submenu page
	 */
	public function renderInvoicesSettingsPage() {
		$current_tab = ( empty( $_GET['tab'] ) ) ? 'settings' : sanitize_text_field( urldecode( $_GET['tab'] ) );

		include 'wc-functions.php';

		$docs_link = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/docs/faktury-wordpress-docs/' : 'https://www.wpdesk.net/docs/flexible-invoices-wordpress-docs/';
		$docs_link .= '?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-invoices-docs-link';

		echo $this->_plugin->loadTemplate( 'submenu_invoices', 'settings', array(
				'current_tab' => $current_tab,
				'plugin'      => $this->_plugin,
				'docs_link'   => $docs_link,
			)
		);

	}

	/**
	 * Get all roles without administrator role.
	 *
	 * @return array
	 */
	public function getRoles() {
		$roles = wp_roles()->get_names();
		unset( $roles[ self::ADMIN_ROLE ] );

		return (array) $roles;
	}

	/**
	 * Check if the roles are in the table
	 *
	 * @param array $roles sended roles value
	 *
	 * @return array
	 */
	private function filterRoles( array $roles ) {
		$wp_roles = $this->getRoles();
		$has_role = array();

		foreach ( $roles as $role_name ) {
			if ( array_key_exists( $role_name, $wp_roles ) ) {
				$has_role[] = $role_name;
			}
		}

		return $has_role;
	}

	/**
	 * Sanitize incoming data settings
	 *
	 * @param string|array $value setting value
	 *
	 * @return string|array
	 */
	public function sanitizeSettingValue( $value ) {
		if ( is_array( $value ) ) {
			$value = array_map( 'wp_unslash', $value );
			$value = array_map( 'sanitize_text_field', $value );
		} else {
			$value = sanitize_text_field( wp_unslash( ( $value ) ) );
		}

		return $value;
	}

	/**
	 * Update settings.
	 *
	 * @param string $name
	 * @param mixed  $value
	 */
	private function update_option( $name, $value, $escape = 'text_field' ) {
		update_option( self::SETTINGS_PREFIX . '_' . $name, $value );
	}

	/**
	 * Update settings
	 */
	public function update_settings_action() {
		$post_data   = wp_unslash( $_POST );
		$nonce_name  = isset( $post_data['flexible_invoices_settings'] ) ? $post_data['flexible_invoices_settings'] : null;
		$option_page = isset( $post_data['option_page'] ) ? $post_data['option_page'] : null;
		$tab         = isset( $_GET['tab'] ) ? $_GET['tab'] : '';

		if ( 'inspire_invoices_settings' === $option_page && wp_verify_nonce( $nonce_name, 'save_settings' ) && current_user_can( 'manage_options' ) ) {
			if ( 'settings' === $tab || empty( $tab ) ) {
				$order_start_invoice_number = get_option( 'inspire_invoices_order_start_invoice_number', '' );
				update_option( 'inspire_invoices_tax_payer', '' );
				update_option( 'inspire_invoices_show_signatures', '' );
				update_option( 'inspire_invoices_signature_user', '' );
				update_option( 'inspire_invoices_hide_vat', '' );
				update_option( 'inspire_invoices_hide_vat_number', '' );
				update_option( 'inspire_invoices_roles', '' );

				foreach ( $post_data[ $this->_plugin->getPluginNamespace() ] as $name => $value ) {
					$this->update_option( $name, $this->sanitizeSettingValue( $value ) );
					if ( 'payment_methods' === $name ) {
						$this->update_option( $name, sanitize_textarea_field( $value ) );
					}
					if ( 'signature_user' === $name ) {
						$this->update_option( $name, (int) $value );
					}
					if ( 'invoices_notice' === $name ) {
						$this->update_option( $name, sanitize_textarea_field( $value ) );
					}
					if ( 'company_address' === $name ) {
						$this->update_option( $name, sanitize_textarea_field( $value ) );
					}
					if ( 'order_number_prefix' === $name ) {
						$this->update_option( $name, trim(strip_tags($value), "\t\n\r\0\x0B") );
					}
					if ( 'order_number_suffix' === $name ) {
						$this->update_option( $name, sanitize_textarea_field( $value ) );
					}
					if ( 'roles' === $name && is_array( $value ) ) {
						$roles = $this->filterRoles( $value );
						$this->update_option( $name, $roles );
					}
				}

				if ( $order_start_invoice_number != '' && $order_start_invoice_number != get_option( 'inspire_invoices_order_start_invoice_number', '' ) ) {
					update_option( 'inspire_invoices_order_start_invoice_number_timestamp', current_time( 'timestamp' ) );
				}
			}

			if ( 'corrections' === $tab ) {
				$correction_start_invoice_number = get_option( 'inspire_invoices_correction_start_invoice_number', '' );
				update_option( 'inspire_invoices_enable_corrections', '' );
				foreach ( $post_data[ $this->_plugin->getPluginNamespace() ] as $name => $value ) {
					$this->update_option( $name, sanitize_text_field( $value ) );
					if ( 'correction_prefix' === $name ) {
						$this->update_option( $name, $value );
					}
					if ( 'correction_suffix' === $name ) {
						$this->update_option( $name, $value );
					}
				}
				if ( $correction_start_invoice_number != '' && $correction_start_invoice_number != get_option( 'inspire_invoices_correction_start_invoice_number', '' ) ) {
					update_option( 'inspire_invoices_correction_start_invoice_number_timestamp', current_time( 'timestamp' ) );
				}
			}

			if ( 'currency' === $tab ) {
				update_option( 'inspire_invoices_currency', $post_data['inspire_invoices_currency'] );
			}

			if ( 'tax' === $tab ) {
				include 'wc-functions.php';
				$inspire_invoices_tax = array();
				if ( isset( $post_data['inspire_invoices_tax'] ) ) {
					$inspire_invoices_tax = $post_data['inspire_invoices_tax'];
					foreach ( $inspire_invoices_tax as $key => $val ) {
						$inspire_invoices_tax[ $key ]['rate'] = wc_format_decimal( $inspire_invoices_tax[ $key ]['rate'] );
					}
				}
				update_option( 'inspire_invoices_tax', $inspire_invoices_tax );
			}
		}
	}

	public function getSettingValue( $name, $default = null ) {
		$ret = $this->_plugin->getSettingValue( $name, $default );
		if ( is_array( $ret ) ) {
			return array_map( 'esc_attr', $ret );
		}

		return esc_attr( $ret );
	}

	public function generateReportAction() {
		if ( isset( $_GET['report_download'] ) && wp_verify_nonce( $_GET['report_download'], 'download_report' ) && current_user_can( 'manage_options' ) ) {
			$currency = isset( $_GET['currency'] ) ? $_GET['currency'] : false;
			if( $currency ) {
				$currency_decimal_separator = '.';
				$inspire_invoices_currency = get_option( 'inspire_invoices_currency', array() );
				if ( is_array( $inspire_invoices_currency ) ) {
					foreach ( $inspire_invoices_currency as $currency_config ) {
						if ( $currency_config['currency'] == $currency ) {
							$currency_decimal_separator = $currency_config['decimal_separator'];
							break;
						}
					}
				}

				echo $this->_plugin->loadTemplate( 'generated_report', 'invoice', array(
					'plugin'                     => $this->_plugin,
					'currency_decimal_separator' => $currency_decimal_separator
				) );
			}
			die();
		}
	}
}
