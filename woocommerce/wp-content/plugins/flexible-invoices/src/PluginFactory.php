<?php
/**
 * Plugin main class.
 *
 * @package InvoicesWooCommerce
 */

namespace WPDesk\FlexibleInvoices;

use Psr\Log\LoggerAwareTrait;
use WPDeskFIVendor\WPDesk\Notice\Notice;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;

use WPDeskFIVendor\WPDesk_Plugin_Info;

/**
 * Main plugin class. The most important flow decisions are made here.
 */
class PluginFactory extends AbstractPlugin implements HookableCollection {

	use HookableParent;
	use LoggerAwareTrait;

	const FIW_PRO_PLUGIN_SLUG = 'flexible-invoices-woocommerce/flexible-invoices-woocommerce.php';

	/**
	 * @var WPDesk_Plugin_Info;
	 */
	protected $plugin_info;

	/**
	 * @param WPDesk_Plugin_Info $plugin_info Plugin data.
	 */
	public function __construct( $plugin_info ) {
		parent::__construct( $plugin_info );
	}

	/**
	 * Fires hooks.
	 */
	public function hooks() {
		parent::hooks();
		add_action( 'plugins_loaded', [ $this, 'plugin_factory' ], 1000 );
	}

	/**
	 * @param $plugin
	 */
	public function plugin_factory( $plugin ) {
		if ( $this->is_plugin_active( self::FIW_PRO_PLUGIN_SLUG ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
			$plugin_data = get_plugin_data( trailingslashit( WP_PLUGIN_DIR ) . self::FIW_PRO_PLUGIN_SLUG );
			$version     = isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : 0;
			if ( $version && version_compare( $version, '3.0.0', '<' ) ) {
				$this->fire_old_plugin();
			} else {
				$this->show_deactivation_notice();
			}
		} else {
			( new Plugin( $this->plugin_info ) )->init();
		}
	}

	/**
	 * Show disable notice for PRO.
	 */
	public function show_deactivation_notice() {
		add_action( 'init', function () {
			$action = 'deactivate';
			$plugin = 'flexible-invoices/flexible-invoices.php';
			$url    = sprintf( admin_url( 'plugins.php?action=' . $action . '&plugin=%s&plugin_status=all&paged=1&s' ), $plugin );
			$url    = wp_nonce_url( $url, $action . '-plugin_' . $plugin );
			new Notice(
				sprintf(
				// Translators: link.
					__( '<strong>Flexible Invoices</strong> plugin can be removed now since the PRO version took over its functionalities.%1$s%2$sClick here%3$s to deactivate "Flexible Invoices" plugin.', 'flexible-invoices' ),
					'<br/>',
					'<a href="' . $url . '">',
					'</a>'
				)
			);
		} );
	}

	/**
	 * Fire old plugin main class.
	 */
	private function fire_old_plugin() {
		( new \Flexible_Invoices_Plugin( $this->plugin_info ) )->init();
		add_action( 'admin_init', [ $this, 'show_notice_for_outdated_fiw' ] );
	}

	/**
	 * @param string $plugin
	 *
	 * @return bool
	 */
	public function is_plugin_active( $plugin ) {
		$plugin_dir = trailingslashit( WP_PLUGIN_DIR );
		if ( function_exists( 'is_plugin_active_for_network' ) ) {
			if ( file_exists( $plugin_dir . $plugin ) && is_plugin_active_for_network( $plugin ) ) {
				return true;
			}
		}

		return file_exists( $plugin_dir . $plugin ) && in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
	}

	/**
	 * Show notice about outdated version of FI Pro.
	 */
	public function show_notice_for_outdated_fiw() {
		new Notice( __( 'You are using <strong>an outdated version of Flexible Invoices WooCommerce</strong>. We recommended upgrade to version 3.0+. Do it now!', 'flexible-invoices' ), Notice::NOTICE_TYPE_ERROR );
	}
}
