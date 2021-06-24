<?php
/**
 * Plugin main class.
 *
 * @package InvoicesWooCommerce
 */

namespace WPDesk\FlexibleInvoices;

use WPDesk\FlexibleInvoices\Addons\Filters\AdvancedFiltersAddon;
use WPDesk\FlexibleInvoices\Addons\Sending\SettingsIntegration;
use WPDesk\FlexibleInvoices\RateNotice\RateNotice;
use WPDesk\FlexibleInvoices\Settings\DocumentsSettingsTabsReplacer;
use WPDesk\FlexibleInvoices\Settings\WooCommerceSettingsTabsReplacer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType;
use WPDeskFIVendor\WPDesk\ShowDecision\PostTypeStrategy;
use WPDesk\FlexibleInvoices\RateNotice\TwoWeeksNotice;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
use WPDeskFIVendor\WPDesk\Logger\WPDeskLoggerFactory;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;

use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
use WPDeskFIVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use WPDeskFIVendor\WPDesk\View\Resolver\ChainResolver;
use WPDeskFIVendor\WPDesk\View\Resolver\DirResolver;
use WPDeskFIVendor\WPDesk_Plugin_Info;

/**
 * Main plugin class. The most important flow decisions are made here.
 */
class Plugin extends AbstractPlugin implements LoggerAwareInterface, HookableCollection {

	use HookableParent;
	use LoggerAwareTrait;

	/**
	 * @var string
	 */
	private $plugin_text_domain;
	/**
	 * @var string
	 */
	private $plugin_path;

	/**
	 * @var string
	 */
	private $pro_url;

	/**
	 * @var Renderer
	 */
	private $renderer;

	/**
	 * @param WPDesk_Plugin_Info $plugin_info Plugin data.
	 */
	public function __construct( $plugin_info ) {
		$this->plugin_info = $plugin_info;
		parent::__construct( $this->plugin_info );

		$this->plugin_url         = trailingslashit( $this->plugin_info->get_plugin_url() );
		$this->plugin_path        = trailingslashit( $this->plugin_info->get_plugin_dir() );
		$this->plugin_text_domain = $this->plugin_info->get_text_domain();
		$this->plugin_namespace   = $this->plugin_text_domain;
		$this->setLogger( $this->is_debug_mode() ? ( new WPDeskLoggerFactory() )->createWPDeskLogger() : new NullLogger() );
	}

	/**
	 * Init renderer.
	 */
	private function init_renderer() {
		$resolver = new ChainResolver();
		$resolver->appendResolver( new DirResolver( $this->plugin_path . '/templates/' ) );
		$this->renderer = new SimplePhpRenderer( $resolver );
	}

	/**
	 * Init plugin.
	 */
	public function init() {
		$this->load_compatibility_dependencies();
		$this->init_renderer();
		$this->hooks();
		$this->hooks_on_hookable_objects();
	}

	/**
	 * Fires hooks
	 */
	public function hooks() {
		parent::hooks();
		$integration = new PluginInvoiceIntegration( $this->plugin_info, $this->logger );
		$this->add_hookable( $integration );
		$this->add_hookable( new DocumentsSettingsTabsReplacer( $integration->get_strategy() ) );
		$this->add_hookable( new WooCommerceSettingsTabsReplacer( $integration->get_strategy() ) );
		if ( WooCommerce::is_active() ) {
			( new Tracker\Tracker( $this->plugin_info->get_plugin_file_name() ) )->hooks();
			( new Tracker\UsageDataTracker( $this->plugin_info->get_plugin_file_name() ) )->hooks();
			Translator::$text_domain = $this->plugin_text_domain;
			Translator::init( $this->plugin_info );
		}

		$this->add_hookable( new AdvancedFiltersAddon( $this->renderer, $this->plugin_url ) );
		$this->add_hookable( new SettingsIntegration( $this->plugin_path, $this->plugin_url ) );

		add_action( 'woocommerce_init', function () {
			if ( is_admin() ) {
				( new RateNotice(
					[ new TwoWeeksNotice( $this->plugin_url . '/assets', new PostTypeStrategy( RegisterPostType::POST_TYPE_NAME ) ) ]
				) )->hooks();
			}
		} );

	}

	/**
	 * Load compatibility dependencies with older plugins for prevent fatal errors.
	 */
	public function load_compatibility_dependencies() {
		require_once __DIR__ . '/Compatibility/functions.php';
	}

	/**
	 * Returns true when debug mode is on.
	 *
	 * @return bool
	 */
	private function is_debug_mode() {
		$helper_options = get_option( 'wpdesk_helper_options', [] );

		return isset( $helper_options['debug_log'] ) && '1' === $helper_options['debug_log'];
	}

	/**
	 * Delete this code when we drop support for old version of FIW.
	 *
	 * @return array
	 */
	private function dummy_translations_for_old_versions() {
		return [
			__( 'Invoices Settings', 'flexible-invoices' ),
			__( 'For prefixes and suffixes use the following short tags: {DD} for day, {MM} for month, {YYYY} for year.', 'flexible-invoices' ),
			__( 'Invoice Number Reset', 'flexible-invoices' ),
			__( 'Next Invoice Number', 'flexible-invoices' ),
			__( 'Invoice Prefix', 'flexible-invoices' ),
			__( 'Invoice Suffix', 'flexible-invoices' ),
			__( 'Invoice Notes', 'flexible-invoices' ),
			__( 'Advanced Settings', 'flexible-invoices' ),
			__( 'Insert currency', 'flexible-invoices' ),
			__( 'Delete selected currency', 'flexible-invoices' ),
			__( 'Insert rate', 'flexible-invoices' ),
			__( 'Delete selected rate', 'flexible-invoices' ),
			__( 'Correction Number Reset', 'flexible-invoices' ),
			__( 'Next Correction Number', 'flexible-invoices' ),
			__( 'Correction Prefix', 'flexible-invoices' ),
			__( 'Correction Suffix', 'flexible-invoices' ),
			__( 'Correction Default Due Time', 'flexible-invoices' ),
			__( 'Correction Reason', 'flexible-invoices' ),
		];
	}

	/**
	 * Plugin action links
	 *
	 * @param array $links List of links.
	 *
	 * @return array
	 */
	public function links_filter( $links ) {
		unset( $links['0'] );
		$is_pl        = 'pl_PL' === get_locale();
		$support_url  = $is_pl ? 'https://www.wpdesk.pl/support/' : 'https://flexibleinvoices.com/support/';
		$settings_url = admin_url( 'edit.php?post_type=inspire_invoice&page=invoices_settings' );
		$docs_url     = $is_pl ? 'https://www.wpdesk.pl/docs/faktury-wordpress-docs/' : 'https://docs.flexibleinvoices.com/';
		$docs_url     .= '?utm_source=wp-admin-plugins&utm_medium=quick-link&utm_campaign=flexible-invoices-docs-link';
		$pro_url      = $is_pl ? 'https://www.wpdesk.pl/sklep/faktury-woocommerce/' : 'https://www.flexibleinvoices.com/products/flexible-invoices-woocommerce/';
		$pro_url      .= '?utm_source=wp-admin-plugins&utm_medium=quick-link&utm_campaign=flexible-invoices-plugins-upgrade-link';

		$plugin_links['settings'] = '<a href="' . $settings_url . '">' . __( 'Settings', 'flexible-invoices' ) . '</a>';
		$plugin_links['docs']     = '<a href="' . $docs_url . '" target="_blank">' . __( 'Docs', 'flexible-invoices' ) . '</a>';
		$plugin_links['upgrade']  = '<a href="' . $pro_url . '" target="_blank" style="color:#d64e07;font-weight:bold;">' . __( 'Buy PRO →', 'flexible-invoices' ) . '</a>';
		$plugin_links['support']  = '<a href="' . $support_url . '" target="_blank">' . __( 'Support', 'flexible-invoices' ) . '</a>';

		return array_merge( $plugin_links, $links );
	}

}
