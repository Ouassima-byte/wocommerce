<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
/**
 * WooCommerce settings.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Strategy
 */
class SettingsWoocommerceStrategy extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\AbstractSettingsStrategy
{
    /**
     * @param Settings $settings
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings)
    {
        parent::__construct($settings);
    }
    /**
     * Set payment methods.
     */
    public function get_payment_methods()
    {
        $payment_methods = parent::get_payment_methods();
        $gateways = \WC()->payment_gateways->payment_gateways();
        $woo_payment_methods = array();
        foreach ($gateways as $gateway) {
            $woo_payment_methods['woocommerce'][$gateway->id] = $gateway->title;
        }
        return \array_merge($payment_methods, $woo_payment_methods);
    }
    /**
     * @return array
     */
    public function get_order_statuses()
    {
        $woocommerce_statuses = \wc_get_order_statuses();
        foreach ($woocommerce_statuses as $status => $status_display) {
            $status = \str_replace('wc-', '', $status);
            $statuses_options[$status] = $status_display;
        }
        return $statuses_options;
    }
}
