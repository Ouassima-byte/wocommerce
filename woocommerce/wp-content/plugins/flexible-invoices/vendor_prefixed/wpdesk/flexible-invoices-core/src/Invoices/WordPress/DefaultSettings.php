<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Set default settings for invoices.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WordPress
 */
class DefaultSettings implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Fire hooks.
     */
    public function hooks()
    {
        \add_action('fi/core/settings/tabs/saved', [$this, 'insert_taxes_if_empty']);
        \add_action('init', [$this, 'update_settings'], 100);
    }
    /**
     * Update plugin settings.
     */
    public function update_settings()
    {
        if ('yes' !== \get_option('inspire_invoices_default_values')) {
            $this->insert_default_values();
            $this->update_option_for_woocommerce();
            $this->settings_upgrade_v3();
            \update_option('inspire_invoices_default_values', 'yes');
        }
    }
    /**
     * @return array
     *
     * @since 3.0.0
     */
    private function get_default_taxes()
    {
        return array(array('rate' => 23, 'name' => '23%'), array('rate' => 22, 'name' => '22%'), array('rate' => 21, 'name' => '21%'), array('rate' => 20, 'name' => '20%'), array('rate' => 19, 'name' => '19%'), array('rate' => 8, 'name' => '8%'), array('rate' => 7, 'name' => '7%'), array('rate' => 5, 'name' => '5%'), array('rate' => 3, 'name' => '3%'), array('rate' => 0, 'name' => '0%'), array('rate' => '0', 'name' => 'zw.'), array('rate' => '0', 'name' => 'np.'));
    }
    /**
     * Insert default values.
     *
     * @since 3.0.0
     */
    private function insert_default_values()
    {
        $settings_to_add = ['inspire_invoices_woocommerce_add_nip_field' => 'on', 'inspire_invoices_woocommerce_add_invoice_ask_field' => 'no', 'inspire_invoices_woocommerce_nip_label' => \__('VAT Number', 'flexible-invoices'), 'inspire_invoices_currency' => \unserialize('a:3:{i:2;a:5:{s:8:"currency";s:3:"PLN";s:17:"currency_position";s:11:"right_space";s:18:"thousand_separator";s:1:" ";s:17:"decimal_separator";s:1:",";s:12:"num_decimals";s:1:"2";}i:1;a:5:{s:8:"currency";s:3:"USD";s:17:"currency_position";s:4:"left";s:18:"thousand_separator";s:1:",";s:17:"decimal_separator";s:1:".";s:12:"num_decimals";s:1:"2";}i:3;a:5:{s:8:"currency";s:3:"EUR";s:17:"currency_position";s:4:"left";s:18:"thousand_separator";s:1:",";s:17:"decimal_separator";s:1:".";s:12:"num_decimals";s:1:"2";}}'), 'inspire_invoices_invoice_start_number' => 1, 'inspire_invoices_invoice_number_reset_type' => 'month', 'inspire_invoices_invoice_number_prefix' => \__('Invoice ', 'flexible-invoices'), 'inspire_invoices_invoice_number_suffix' => '/{MM}/{YYYY}', 'inspire_invoices_correction_start_number' => 1, 'inspire_invoices_correction_number_reset_type' => 'year', 'inspire_invoices_correction_number_prefix' => \__('Corrected invoice ', 'flexible-invoices'), 'inspire_invoices_correction_number_suffix' => '/{MM}/{YYYY}', 'inspire_invoices_proforma_start_number' => 1, 'inspire_invoices_proforma_number_reset_type' => 'month', 'inspire_invoices_proforma_number_prefix' => \__('Invoice Proforma ', 'flexible-invoices'), 'inspire_invoices_proforma_number_suffix' => '/{MM}/{YYYY}'];
        foreach ($settings_to_add as $option_name => $option_value) {
            if (!\get_option($option_name)) {
                \update_option($option_name, $option_value);
            }
        }
    }
    /**
     * Init settings values.
     *
     * @since 3.0.0
     */
    public function update_option_for_woocommerce()
    {
        if (\in_array('woocommerce/woocommerce.php', \apply_filters('active_plugins', \get_option('active_plugins')))) {
            if (!\get_option('inspire_invoices_currency_woo_updated')) {
                $inspire_invoices_currency = \get_option('inspire_invoices_currency', array());
                $woo_currency = \get_option('woocommerce_currency', '');
                if ($woo_currency != '') {
                    $add_currency = \true;
                    foreach ($inspire_invoices_currency as $inspire_currency) {
                        if ($inspire_currency['currency'] == $woo_currency) {
                            $add_currency = \false;
                        }
                    }
                    if ($add_currency) {
                        $inspire_invoices_currency[] = array('currency' => $woo_currency, 'currency_position' => \get_option('woocommerce_currency_pos', 'left'), 'thousand_separator' => \get_option('woocommerce_price_thousand_sep', 'left'), 'decimal_separator' => \get_option('woocommerce_price_decimal_sep', 'left'), 'num_decimals' => \get_option('woocommerce_price_num_decimals', 'left'));
                        \update_option('inspire_invoices_currency', $inspire_invoices_currency);
                    }
                }
                \update_option('inspire_invoices_currency_woo_updated', \true);
            }
        }
        if (!\get_option('inspire_invoices_tax_updated')) {
            \update_option('inspire_invoices_tax', $this->get_default_taxes());
            \update_option('inspire_invoices_tax_updated', \true);
        }
    }
    /**
     * Insert taxes after saving settings if they empty.
     *
     * @internal You should not use this directly from another application
     *
     * @since    3.0.0
     */
    public function insert_taxes_if_empty()
    {
        $taxes = \get_option('inspire_invoices_tax');
        if (empty($taxes) || !\is_array($taxes)) {
            \update_option('inspire_invoices_tax', $this->get_default_taxes());
        }
    }
    /**
     * Upgrade settings and meta.
     *
     * @since 3.0.0
     */
    private function settings_upgrade_v3()
    {
        if ((int) \get_option('inspire_invoices_settings_upgrade') < 3) {
            $this->create_new_options();
            $this->insert_corrections_meta_key();
            \update_option('inspire_invoices_settings_upgrade', 3);
        }
    }
    /**
     * Create new meta values for all corrections.
     *
     * @since 3.0.0
     */
    private function insert_corrections_meta_key()
    {
        global $wpdb;
        $corrections = $wpdb->get_results("SELECT * FROM {$wpdb->postmeta} WHERE `meta_key` = '_invoice_corrections'");
        foreach ($corrections as $correction) {
            \update_post_meta($correction->post_id, '_correction_generated', $correction->meta_value);
        }
    }
    /**
     * @since 3.0.0
     */
    private function create_new_options()
    {
        $settings_to_rename = ['inspire_invoices_invoice_number_reset_type' => 'inspire_invoices_number_reset_type', 'inspire_invoices_invoice_start_number' => 'inspire_invoices_order_start_invoice_number', 'inspire_invoices_invoice_number_prefix' => 'inspire_invoices_order_number_prefix', 'inspire_invoices_invoice_number_suffix' => 'inspire_invoices_order_number_suffix', 'inspire_invoices_invoice_auto_paid_status' => 'inspire_invoices_woocommerce_auto_paid_status', 'inspire_invoices_invoice_auto_create_status' => 'inspire_invoices_woocommerce_send_invoice_mail_when_status', 'inspire_invoices_invoice_notes' => 'inspire_invoices_invoices_notice', 'inspire_invoices_invoice_default_due_time' => 'inspire_invoices_pay_date_days', 'inspire_invoices_invoice_date_of_sale_label' => 'inspire_invoices_date_of_sale', 'inspire_invoices_correction_start_number' => 'inspire_invoices_correction_start_invoice_number', 'inspire_invoices_correction_number_prefix' => 'inspire_invoices_correction_prefix', 'inspire_invoices_correction_notes' => 'inspire_invoices_correction_reason', 'inspire_invoices_correction_type' => 'inspire_invoices_correction_number_reset_type'];
        foreach ($settings_to_rename as $new_key => $old_key) {
            $old_option_value = \get_option($old_key);
            if (!empty($old_option_value)) {
                \update_option($new_key, $old_option_value);
            }
        }
    }
}
