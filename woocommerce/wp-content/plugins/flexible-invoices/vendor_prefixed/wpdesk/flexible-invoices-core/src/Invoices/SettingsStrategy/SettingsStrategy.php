<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy;

/**
 * Interface of settings from different sources.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Strategy
 */
interface SettingsStrategy
{
    /**
     * Get currencies data.
     *
     * We don't use WooCommerce currencies settings, only own saved settings.
     *
     * @return array
     */
    public function get_currencies();
    /**
     * Get taxes from settings.
     *
     * @return array
     */
    public function get_taxes();
    /**
     * Get payment statuses.
     *
     * @return array
     */
    public function get_payment_statuses();
    /**
     * Get payment methods.
     *
     * @return array
     */
    public function get_payment_methods();
    /**
     * Get single tax value from settings.
     *
     * @param string $value
     *
     * @return array
     */
    public function get_tax_value($value);
    /**
     * Order statuses in needed when WooCommerce active. Otherwise return only one option for document settings.
     *
     * @return array
     */
    public function get_order_statuses();
}
