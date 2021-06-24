<?php
/**
 * Flexible Invoices. Main class of plugin.
 *
 * @package Flexible Invoices
 */

use WPDeskFIVendor\WPDesk\ShowDecision\PostTypeStrategy;

/**
 * Main class for WordPress plugin
 */
class Flexible_Invoices_Plugin extends \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Activateable {

	const INVOICE_DIRECTORY_NAME = 'wordpress_invoices';
	/**
	 * Script version
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * Script version.
	 *
	 * @var string
	 */
	private $script_version = '1.4';

	/**
	 * Tracker.
	 *
	 * @var WPDesk_Flexible_Invoices_Tracker
	 */
	private $tracker;

	/**
	 * Flexible_Invoices_Plugin constructor.
	 *
	 * @param WPDeskFIVendor\WPDesk_Plugin_Info $plugin_info Plugin data.
	 */
	public function __construct( WPDeskFIVendor\WPDesk_Plugin_Info $plugin_info ) {
		$this->plugin_info = $plugin_info;
		parent::__construct( $this->plugin_info );
		$this->load_plugin_functions();
	}

	/**
	 * Get plugin info
	 *
	 * @return WPDesk_Plugin_Info
	 */
	public function get_plugin_info() {
		return $this->plugin_info;
	}

	/**
	 * Init base variables for plugin
	 */
	public function init_base_variables() {
		$this->plugin_url         = $this->plugin_info->get_plugin_url();
		$this->plugin_path        = $this->plugin_info->get_plugin_dir();
		$this->template_path      = $this->plugin_info->get_text_domain();
		$this->plugin_text_domain = $this->plugin_info->get_text_domain();
		$this->plugin_namespace   = $this->plugin_info->get_text_domain();
		$this->template_path      = $this->plugin_info->get_text_domain();
		$this->version            = $this->plugin_info->get_version();
		$this->default_view_args  = array(
			'plugin_url' => $this->get_plugin_url(),
		);
	}

	private function load_plugin_functions() {
		$plugin_file = $this->get_plugin_file_path();
		require_once __DIR__ . '/core-functions.php';
	}

	/**
	 * Fires hooks
	 */
	public function hooks() {
		parent::hooks();

		Invoice::$plugin = $this;

		$_GLOBALS['inspire_invoices'] = $invoice = Invoice::getInstance(); //phpcs:ignore
		Flexible_Invoices_Translator::init( $this->plugin_info );

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 20 );
		add_filter( 'plugin_action_links_' . plugin_basename( $this->get_plugin_file_path() ), array(
			$this,
			'links_filter'
		) );
		add_action( 'plugins_loaded', array( $this, 'create_tracker' ), 12 );

		$notices = new InvoicesNotices();
		$notices->hooks();

		$this->tracker = new WPDesk_Flexible_Invoices_Tracker();
		$this->tracker->hooks();

		add_action( 'woocommerce_init', function () {
			if ( is_admin() ) {
				( new \WPDesk\FlexibleInvoices\RateNotice\RateNotice(
					[ new \WPDesk\FlexibleInvoices\RateNotice\TwoWeeksNotice( $this->plugin_url . '/assets', new PostTypeStrategy( invoicePostType::POST_TYPE ) ) ]
				) )->hooks();
			}
		} );
	}

	/**
	 * Create tracker
	 */
	public function create_tracker() {
		$tracker_factory = new WPDesk_Tracker_Factory();
		$tracker_factory->create_tracker( basename( dirname( __FILE__ ) ) );
	}

	/**
	 * Activate plugin.
	 *
	 * When the plugin is activated, a directory is created in the upload and .htaccess file.
	 */
	public function activate() {
		$upload_dir         = wp_upload_dir();
		$invoices_directory = trailingslashit( $upload_dir['basedir'] ) . trailingslashit( self::INVOICE_DIRECTORY_NAME );
		if ( wp_mkdir_p( $invoices_directory ) ) {
			file_put_contents( $invoices_directory . '.htaccess', 'deny from all' );
		}
	}

	/**
	 * Load plugin text domain
	 *
	 * @return void
	 */
	public function load_plugin_text_domain() {
		load_plugin_textdomain( $this->get_text_domain(), false, $this->get_namespace() . '/lang/' );
	}

	/**
	 * Enqueue admin scripts.
	 */
	public function admin_enqueue_scripts() {
		$current_screen = get_current_screen();

		if ( in_array( $current_screen->id, array(
			'inspire_invoice',
			'edit-inspire_invoice',
			'inspire_invoice_page_invoices_settings'
		), true ) ) {
			wp_enqueue_style( 'flexible-invoices-admin-style', $this->get_plugin_url() . 'assets/css/admin.css', array(), $this->script_version );
			wp_enqueue_style( 'flexible-invoices-admin-actions-style', $this->get_plugin_url() . 'assets/css/admin-order.css', array(), $this->script_version );
			wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/smoothness/jquery-ui.css', array(), $this->script_version );
		}

		if ( in_array( $current_screen->id, array( 'edit-shop_order', 'shop_order' ), true ) ) {
			wp_enqueue_style( 'flexible-invoices-admin-actions-style', $this->get_plugin_url() . 'assets/css/admin-order.css', array(), $this->script_version );
		}

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui' );
		wp_enqueue_script( 'jquery-ui-datepicker' );

		if ( in_array( $current_screen->id, array(
			'inspire_invoice',
			'edit-inspire_invoice',
			'inspire_invoice_page_invoices_settings',
			'edit-shop_order',
			'shop_order'
		), true ) ) {
			$inspire_invoice_params = array(
				'plugin_url'                    => $this->get_plugin_url(),
				'message_generating'            => __( 'Generate, please wait ...', 'flexible-invoices' ),
				'message_generating_successful' => __( 'Completed successfully.', 'flexible-invoices' ),
				'message_generating_error'      => __( 'An unexpected error occurred: ', 'flexible-invoices' ),
				'message_confirm'               => __( 'Note, all unsaved changes will be lost.', 'flexible-invoices' ),
				'message_invoice_sent'          => __( 'You have sent an invoice to: ', 'flexible-invoices' ),
				'message_invoice_not_sent_woo'  => __( 'You can not send an invoice not issued for the WooCommerce order.', 'flexible-invoices' ),
				'message_not_sent'              => __( 'Could not send invoice.', 'flexible-invoices' ),
				'message_not_saved_changes'     => __( 'Note, unsaved changes will not be included in the email you send.', 'flexible-invoices' ),
				'loading_more'                  => __( 'More...', 'flexible-invoices' ),
				'no_results'                    => __( 'No users.', 'flexible-invoices' ),
				'searching'                     => __( 'Searching...', 'flexible-invoices' ),
				'error_loading'                 => __( 'Searching...', 'flexible-invoices' ),
				'placeholder'                   => __( 'Search user', 'flexible-invoices' ),
				'min_chars'                     => __( 'Minimum length %.', 'flexible-invoices' ),
				'ajax_nonce'                    => wp_create_nonce( Invoice::PLUGIN_NAMESPACE ),
			);

			wp_enqueue_script( 'inspire-invoice-admin', $this->get_plugin_url() . 'assets/js/admin.js', array(
				'jquery',
				'jquery-ui-datepicker'
			), $this->script_version, true );
			wp_localize_script( 'inspire-invoice-admin', 'inspire_invoice_params', $inspire_invoice_params );
		}

		if ( $this->select2_visibility() ) {
			wp_enqueue_style( 'fi-select2-style', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css', array(), $this->script_version );
			if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.1', '>' ) ) {
				wp_enqueue_script( 'selectWoo' );
			} else {
				wp_enqueue_script( 'fi-select2-script', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array(
					'jquery',
					'jquery-ui-sortable'
				), $this->script_version, true );
			}
		}

		if ( 'inspire_invoice_page_invoices_settings' === $current_screen->id ) {
			wp_enqueue_script( 'invoice_wc_tip_js', $this->get_plugin_assets_url() . '/js/woocommerce/jquery.tipTip.min.js', array( 'jquery' ), $this->script_version, true );
			wp_enqueue_script( 'invoice_wc_settings_js', $this->get_plugin_assets_url() . '/js/woocommerce/settings.min.js', array(
				'jquery',
				'jquery-ui-color',
				'jquery-ui-sortable'
			), $this->script_version, true );

			$locale      = localeconv();
			$decimal     = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';
			$mon_decimal = stripslashes( get_option( 'woocommerce_price_decimal_sep', '.' ) );
			$params      = array(
				// translators:decimal format.
				'i18n_decimal_error'               => sprintf( __( 'Please enter in decimal (%s) format without thousand separators.', 'woocommerce' ), $decimal ),
				// translators:monetary decimal.
				'i18n_mon_decimal_error'           => sprintf( __( 'Please enter in monetary decimal (%s) format without thousand separators and currency symbols.', 'woocommerce' ), $mon_decimal ),
				'i18n_country_iso_error'           => __( 'Please enter in country code with two capital letters.', 'woocommerce' ),
				'i18_sale_less_than_regular_error' => __( 'Please enter in a value less than the regular price.', 'woocommerce' ),
				'decimal_point'                    => $decimal,
				'mon_decimal_point'                => $mon_decimal,
			);

			wp_enqueue_script( 'invoice_wc_admin', $this->get_plugin_assets_url() . '/js/woocommerce/woocommerce_admin.min.js', array(
				'jquery',
				'jquery-ui-sortable'
			), $this->script_version, true );
			wp_localize_script( 'invoice_wc_admin', 'woocommerce_admin', $params );
		}
	}

	/**
	 * Check current screen for select2 scripts
	 *
	 * @return bool
	 */
	protected function select2_visibility() {
		$current_screen = get_current_screen();

		return ( 'inspire_invoice' === $current_screen->id || 'edit-inspire_invoice' === $current_screen->id || 'inspire_invoice_page_invoices_settings' === $current_screen->id );
	}

	/**
	 * Plugin action links
	 *
	 * @param array $links List of links.
	 *
	 * @return array
	 */
	public function links_filter( $links ) {
		$docs_link    = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/docs/faktury-wordpress-docs/' : 'https://www.wpdesk.net/docs/flexible-invoices-wordpress-docs/';
		$docs_link    .= '?utm_source=wp-admin-plugins&utm_medium=quick-link&utm_campaign=flexible-invoices-docs-link';
		$support_link = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/support/' : 'https://www.wpdesk.net/support';

		$plugin_links = array(
			'<a href="' . admin_url( 'edit.php?post_type=inspire_invoice&page=invoices_settings' ) . '">' . __( 'Settings', 'flexible-invoices' ) . '</a>',
			'<a href="' . $docs_link . '" target="_blank">' . __( 'Docs', 'flexible-invoices' ) . '</a>',
			'<a href="' . $support_link . '" target="_blank">' . __( 'Support', 'flexible-invoices' ) . '</a>',
		);

		$pro_link = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/faktury-woocommerce/' : 'https://www.wpdesk.net/products/flexible-invoices-woocommerce/';
		$utm      = '?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=flexible-invoices-plugins-upgrade-link';

		if ( ! is_flexible_invoices_woocommerce_active() ) {
			$plugin_links[] = '<a href="' . $pro_link . $utm . '" target="_blank" style="color:#d64e07;font-weight:bold;">' . __( 'Upgrade to PRO', 'flexible-invoices' ) . '</a>';
		}

		return array_merge( $plugin_links, $links );
	}


}
