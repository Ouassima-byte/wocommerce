<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WC_Tax;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\DocumentGetters;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Adds vat types from WooCommerce.
 *
 * This class takes over the logic for the tax rates.
 * Tax rates are only taken from WooCommerce and rate settings disappear from the plugin settings.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce
 */
class Taxes implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_filter('inspire_invoices_vat_types', array($this, 'add_woocommerce_currencies_vat_types_filter'));
        \add_action('fi/core/template/invoice/after_notes', array($this, 'add_reverse_charge_for_no_eu'), 10, 4);
        \add_action('fi/core/template/proforma/after_notes', array($this, 'add_reverse_charge_for_no_eu'), 10, 4);
        \add_action('fi/core/template/correction/after_notes', array($this, 'add_reverse_charge_for_no_eu'), 10, 4);
    }
    /**
     * @param DocumentGetters $document
     */
    public function add_reverse_charge_for_no_eu(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\DocumentGetters $document)
    {
        if (\floatval($document->get_total_tax()) === 0.0) {
            $country = $document->get_customer()->get_country();
            $eu_countries = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::get_european_union_countries();
            $woocommerce_default_country = \get_option('woocommerce_default_country', 0);
            if ($woocommerce_default_country !== $country && !\in_array($country, $eu_countries)) {
                echo '<p>' . \__('Reverse charge', 'flexible-invoices') . '</p>';
            }
        }
    }
    /**
     * @return array
     *
     * @internal You should not use this directly from another application
     */
    public function add_woocommerce_currencies_vat_types_filter()
    {
        global $wpdb;
        $tax_classes = \WC_Tax::get_tax_classes();
        foreach ($tax_classes as $key => $tax_class) {
            $tax_classes[$key] = \sanitize_title($tax_class);
        }
        $found_rates = $wpdb->get_results("\n                SELECT distinct tax_rates.*\n                FROM {$wpdb->prefix}woocommerce_tax_rates as tax_rates\n                ORDER BY CAST(tax_rate as DECIMAL(10,5)) desc, tax_rate_priority, tax_rate_order\n                ");
        $matched_tax_rates = array();
        foreach ($found_rates as $found_rate) {
            if (\trim($found_rate->tax_rate_class) == '' || \in_array($found_rate->tax_rate_class, $tax_classes)) {
                $matched_tax_rates[$found_rate->tax_rate_id] = array('rate' => $found_rate->tax_rate, 'label' => $found_rate->tax_rate_name, 'shipping' => $found_rate->tax_rate_shipping ? 'yes' : 'no', 'compound' => $found_rate->tax_rate_compound ? 'yes' : 'no');
            }
        }
        $rates = $matched_tax_rates;
        $types = array();
        if (!empty($rates)) {
            foreach ($rates as $index => $rate) {
                $name = $rate['label'];
                $types[$index] = array('index' => $index, 'rate' => \floatval($rate['rate']), 'name' => $name);
            }
        }
        return $types;
    }
}
