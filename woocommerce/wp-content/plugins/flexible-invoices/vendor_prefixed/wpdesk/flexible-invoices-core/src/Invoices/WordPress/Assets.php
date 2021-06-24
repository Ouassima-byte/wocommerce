<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
class Assets implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    const SCRIPTS_VERSION = '1.2';
    const INVOICE_NAMESPACE = 'inspire_invoices';
    const INVOICE_PAGE_ID = 'inspire_invoice';
    const INVOICE_EDIT_PAGE_ID = 'edit-inspire_invoice';
    const SETTINGS_PAGE_ID = 'inspire_invoice_page_invoices_settings';
    const DOWNLOAD_PAGE_ID = 'inspire_invoice_page_download';
    const REPORTS_PAGE_ID = 'inspire_invoice_page_flexible-invoices-reports-settings';
    /**
     * @var LibraryInfo
     */
    private $library_info;
    /**
     * @var string
     */
    private $plugin_assets_js;
    /**
     * @var string
     */
    private $plugin_assets_css;
    /**
     * @param LibraryInfo $library_info
     */
    public function __construct($library_info)
    {
        $this->library_info = $library_info;
        $this->set_assets_urls();
    }
    /**
     * Fire hooks.
     */
    public function hooks()
    {
        \add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
    }
    /**
     * Set assets URLs.
     */
    private function set_assets_urls()
    {
        $this->plugin_assets_js = $this->library_info->get_assets_url() . 'js/';
        $this->plugin_assets_css = $this->library_info->get_assets_url() . 'css/';
    }
    /**
     * Admin enqueue scripts.
     */
    public function admin_enqueue_scripts()
    {
        $screen = \get_current_screen();
        $this->enqueue_select2_scripts($screen);
        $this->enqueue_order_action_scripts($screen);
        $this->enqueue_post_type_scripts($screen);
        $this->enqueue_settings_scripts($screen);
        $this->enqueue_product_search_scripts($screen);
    }
    /**
     * @param \WP_Screen $screen
     *
     * @internal You should not use this directly from another application
     */
    private function enqueue_product_search_scripts(\WP_Screen $screen)
    {
        if (\in_array($screen->id, array('inspire_invoice', 'edit-inspire_invoice'), \true)) {
            \wp_enqueue_script('fiw-products', $this->plugin_assets_js . 'products.js', array('fiw-admin'), self::SCRIPTS_VERSION, \true);
            \wp_localize_script('fiw-products', 'fiw_localize', array('nonce' => \wp_create_nonce('fiw_search_products')));
        }
    }
    /**
     * @param \WP_Screen $screen
     *
     * @internal You should not use this directly from another application
     */
    private function enqueue_post_type_scripts(\WP_Screen $screen)
    {
        if (\in_array($screen->id, array('inspire_invoice', 'edit-inspire_invoice'), \true)) {
            \wp_enqueue_style('fiw-admin-style', $this->plugin_assets_css . 'admin.css', array(), self::SCRIPTS_VERSION);
            \wp_enqueue_style('fiw-actions-style', $this->plugin_assets_css . 'admin-order.css', array(), self::SCRIPTS_VERSION);
            \wp_enqueue_style('jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/smoothness/jquery-ui.css', array(), self::SCRIPTS_VERSION);
        }
        \wp_enqueue_script('jquery');
        \wp_enqueue_script('jquery-ui');
        \wp_enqueue_script('jquery-ui-datepicker');
        if (\in_array($screen->id, array('inspire_invoice', 'edit-inspire_invoice', 'edit-shop_order', 'shop_order', 'inspire_invoice_page_invoices_settings'), \true)) {
            $upgrade_link = \get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/faktury-woocommerce/?utm_source=wp-admin-plugins&utm_medium=quick-link&utm_campaign=flexible-invoices-plugins-upgrade-link' : 'https://www.flexibleinvoices.com/products/flexible-invoices-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=flexible-invoices-plugins-upgrade-link';
            $inspire_invoice_params = array('plugin_url' => $this->library_info, 'message_generating' => \__('Generate, please wait ...', 'flexible-invoices'), 'message_generating_successful' => \__('Completed successfully.', 'flexible-invoices'), 'message_generating_error' => \__('An unexpected error occurred: ', 'flexible-invoices'), 'message_confirm' => \__('Note, all unsaved changes will be lost.', 'flexible-invoices'), 'message_invoice_sent' => \__('You have sent an invoice to: ', 'flexible-invoices'), 'message_invoice_not_sent_woo' => \__('You can not send an invoice not issued for the WooCommerce order.', 'flexible-invoices'), 'message_not_sent' => \__('Could not send invoice.', 'flexible-invoices'), 'message_not_saved_changes' => \__('Note, unsaved changes will not be included in the email you send.', 'flexible-invoices'), 'select2_placeholder' => \__('Search...', 'flexible-invoices'), 'select2_min_chars' => \__('Minimum length %.', 'flexible-invoices'), 'select2_loading_more' => \__('More...', 'flexible-invoices'), 'select2_no_results' => \__('No results.', 'flexible-invoices'), 'select2_searching' => \__('Searching...', 'flexible-invoices'), 'select2_error_loading' => \__('Cannot load data...', 'flexible-invoices'), 'get_pro_version_text' => \__('Upgrade to PRO', 'flexible-invoices'), 'get_pro_version_url' => $upgrade_link, 'ajax_nonce' => \wp_create_nonce(self::INVOICE_NAMESPACE));
            \wp_enqueue_script('fiw-admin', $this->plugin_assets_js . 'admin.js', ['jquery', 'jquery-ui-datepicker'], self::SCRIPTS_VERSION, \true);
            \wp_localize_script('fiw-admin', 'inspire_invoice_params', $inspire_invoice_params);
        }
    }
    /**
     * @param \WP_Screen $screen
     *
     * @internal You should not use this directly from another application
     */
    private function enqueue_settings_scripts(\WP_Screen $screen)
    {
        if (\in_array($screen->id, [self::SETTINGS_PAGE_ID, self::DOWNLOAD_PAGE_ID, self::REPORTS_PAGE_ID])) {
            \wp_enqueue_script('jquery-ui');
            \wp_enqueue_script('jquery-ui-datepicker');
            \wp_enqueue_media();
            \wp_enqueue_style('jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/smoothness/jquery-ui.css', array(), self::SCRIPTS_VERSION);
            \wp_enqueue_style('fiw-settings-style', $this->plugin_assets_css . 'settings.css', array(), self::SCRIPTS_VERSION);
            \wp_enqueue_script('fiw-settings', $this->plugin_assets_js . 'settings.js', ['jquery'], self::SCRIPTS_VERSION, \true);
            \wp_enqueue_script('fiw-tip-tip', $this->plugin_assets_js . 'jquery.tipTip.js', ['jquery'], self::SCRIPTS_VERSION);
        }
    }
    /**
     * @param \WP_Screen $screen
     */
    private function enqueue_order_action_scripts(\WP_Screen $screen)
    {
        if (\in_array($screen->id, array('edit-shop_order', 'shop_order'), \true)) {
            \wp_enqueue_style('fiw-order-style', $this->plugin_assets_css . 'admin-order.css', array(), self::SCRIPTS_VERSION);
            \wp_enqueue_script('fiw-order', $this->plugin_assets_js . 'orders.js', ['jquery'], self::SCRIPTS_VERSION, \true);
        }
    }
    /**
     * @param \WP_Screen $screen
     *
     * @internal You should not use this directly from another application
     */
    private function enqueue_select2_scripts(\WP_Screen $screen)
    {
        if ($this->select2_visibility()) {
            \wp_enqueue_style('fiw-select2-style', $this->plugin_assets_css . 'select2.min.css', [], self::SCRIPTS_VERSION);
            \wp_enqueue_script('fiw-select2-pl', $this->plugin_assets_js . 'select2-pl.js', ['jquery'], self::SCRIPTS_VERSION, \true);
            \wp_enqueue_script('fiw-select2-script', $this->plugin_assets_js . 'select2.min.js', ['jquery'], self::SCRIPTS_VERSION, \false);
        }
    }
    /**
     * Check current screen for select2 scripts
     *
     * @return bool
     *
     *
     */
    private function select2_visibility()
    {
        $current_screen = \get_current_screen();
        return 'inspire_invoice' === $current_screen->id || 'edit-inspire_invoice' === $current_screen->id || 'inspire_invoice_page_invoices_settings' === $current_screen->id || 'inspire_invoice_page_flexible-invoices-settings' === $current_screen->id;
    }
}
