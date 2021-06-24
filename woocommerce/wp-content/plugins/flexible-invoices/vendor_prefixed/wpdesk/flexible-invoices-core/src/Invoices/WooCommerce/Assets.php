<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Load custom scripts and styles for WooCommerce.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce
 */
class Assets implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    const SCRIPTS_VERSION = '1.1';
    /**
     * @var string
     */
    private $library_url;
    /**
     * @var string
     */
    private $library_assets_js;
    /**
     * @var string
     */
    private $library_assets_css;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @param Settings $settings
     * @param string   $library_url
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings, $library_url)
    {
        $this->library_url = $library_url;
        $this->settings = $settings;
        $this->set_assets_urls();
    }
    /**
     * Fire hooks.
     */
    public function hooks()
    {
        \add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }
    /**
     * Set assets URLs.
     */
    private function set_assets_urls()
    {
        $this->library_assets_js = \trailingslashit($this->library_url) . '/assets/js/';
        $this->library_assets_css = \trailingslashit($this->library_url) . '/assets/css/';
    }
    /**
     * Admin enqueue scripts.
     */
    public function enqueue_scripts()
    {
        $this->enqueue_checkout_scripts();
    }
    /**
     * @internal You should not use this directly from another application
     */
    private function enqueue_checkout_scripts()
    {
        if (\is_checkout() && $this->settings->get('woocommerce_add_invoice_ask_field') === 'yes' && $this->settings->get('woocommerce_nip_required') !== 'yes') {
            \wp_enqueue_style('fiw-checkout', $this->library_assets_css . 'checkout.css', '', \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Assets::SCRIPTS_VERSION);
            \wp_enqueue_script('fiw-checkout', $this->library_assets_js . 'checkout.js', ['jquery'], \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Assets::SCRIPTS_VERSION, \true);
        }
    }
}
