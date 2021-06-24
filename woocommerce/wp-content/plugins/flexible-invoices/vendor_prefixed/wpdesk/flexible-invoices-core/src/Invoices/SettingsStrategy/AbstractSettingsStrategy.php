<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
/**
 * @package WPDesk\Library\FlexibleInvoicesCore\Strategy
 */
abstract class AbstractSettingsStrategy implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy
{
    /**
     * @var array
     */
    protected $taxes = [];
    /**
     * @var array
     */
    protected $currencies = [];
    /**
     * @var array
     */
    protected $payment_statuses = [];
    /**
     * @var array
     */
    protected $payment_methods = [];
    /**
     * @var Settings
     */
    protected $settings;
    /**
     * @param Settings $settings
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings)
    {
        $this->settings = $settings;
    }
    /**
     * Get currencies from option
     */
    public function get_currencies()
    {
        if ($this->settings->has('currency')) {
            $currencies_options = $this->settings->get('currency');
            $currencies = array();
            if (\is_array($currencies_options)) {
                foreach ($currencies_options as $currency) {
                    $currencies[$currency['currency']] = $currency['currency'];
                }
            }
            return $currencies;
        }
        return [];
    }
    /**
     * Get taxes from option
     */
    public function get_taxes()
    {
        $taxes = $this->settings->get('tax');
        $rates = array();
        $index = 0;
        foreach ($taxes as $tax) {
            $rates[] = array('index' => $index, 'rate' => $tax['rate'], 'name' => $tax['name']);
            $index++;
        }
        return \apply_filters('inspire_invoices_vat_types', $rates);
    }
    /**
     * @return array
     */
    public function get_payment_methods()
    {
        $payment_methods_option = \explode("\n", $this->settings->get('payment_methods', \implode("\n", array('bank-transfer' => \__('Bank transfer', 'flexible-invoices'), 'cash' => \__('Cash', 'flexible-invoices'), 'other' => \__('Other', 'flexible-invoices')))));
        $payment_methods = array();
        foreach ($payment_methods_option as $payment_method) {
            $payment_methods[\sanitize_title($payment_method)] = $payment_method;
        }
        return ['standard' => $payment_methods];
    }
    /**
     * @return array
     */
    public function get_payment_statuses()
    {
        return (array) \apply_filters('inspire_invoices_payment_statuses', array('topay' => \__('Due', 'flexible-invoices'), 'paid' => \__('Paid', 'flexible-invoices')));
    }
    /**
     * @param string $value
     *
     * @return array
     */
    public function get_tax_value($value)
    {
        foreach ($this->get_taxes() as $tax) {
            if ($tax['rate'] === $value) {
                return (array) $tax;
            }
        }
        return ['rate' => 0, 'name' => 0, '' => ''];
    }
    public function get_settings()
    {
        return $this->settings;
    }
}
