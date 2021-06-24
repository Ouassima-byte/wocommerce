<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs;
/**
 * Define currency settings for PDF printing.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Helpers
 */
class Currency
{
    /**
     * @var array
     */
    private $currencies = [];
    /**
     * @var string
     */
    private $currency_slug;
    /**
     * @param string $currency_slug
     */
    public function __construct($currency_slug)
    {
        $this->currency_slug = $currency_slug;
        $this->prepare_currencies_settings();
    }
    /**
     * Prepare currencies settings.
     */
    private function prepare_currencies_settings()
    {
        $option = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings();
        $currency_options = $option->get('currency');
        foreach ($currency_options as $currency) {
            $currency_slug = $currency['currency'];
            $this->currencies[$currency_slug]['decimal_separator'] = $currency['decimal_separator'];
            $this->currencies[$currency_slug]['thousand_separator'] = $currency['thousand_separator'];
            $this->currencies[$currency_slug]['currency_position'] = $currency['currency_position'];
            $this->currencies[$currency_slug]['currency_symbol'] = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs\Currency::get_currency_symbol($currency['currency']);
            $this->currencies[$currency_slug]['currency'] = $currency['currency'];
        }
    }
    /**
     * @return array
     */
    private function get_currency_settings()
    {
        $defaults = ['decimal_separator' => '.', 'thousand_separator' => '', 'currency_position' => 'right_space', 'currency_symbol' => \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs\Currency::get_currency_symbol($this->currency_slug), 'currency' => $this->currency_slug];
        if (isset($this->currencies[$this->currency_slug])) {
            return \wp_parse_args($this->currencies[$this->currency_slug], $defaults);
        } else {
            return $defaults;
        }
    }
    /**
     * @param float $amount
     *
     * @return string
     */
    public function string_as_money($amount = 0.0)
    {
        $sign = '';
        if (\is_string($amount)) {
            $amount = (float) \str_replace(',', '.', $amount);
        }
        if (\floatval($amount) < 0) {
            $sign = '-';
        }
        $currency_option = $this->get_currency_settings();
        $ret = \number_format(\abs(\floatval($amount)), 2, $currency_option['decimal_separator'], $currency_option['thousand_separator']);
        $symbol = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs\Currency::get_currency_symbol($currency_option['currency']);
        /**
         * Filters WooCommerce currency symbols.
         *
         * @param string $symbol   Symbols.
         * @param string $currency Currency.
         *
         * @since 3.0.0
         */
        $currency_symbol = \apply_filters('woocommerce_currency_symbol', $symbol, $currency_option['currency']);
        switch ($currency_option['currency_position']) {
            case 'left':
                $ret = $currency_symbol . $ret;
                break;
            case 'right':
                $ret .= $currency_symbol;
                break;
            case 'left_space':
                $ret = $currency_symbol . ' ' . $ret;
                break;
            case 'right_space':
                $ret .= ' ' . $currency_symbol;
                break;
        }
        $ret = $sign . $ret;
        return $ret;
    }
    /**
     * @param float $value
     *
     * @return string
     */
    public function number_format($value)
    {
        if (\is_string($value)) {
            $value = (float) \str_replace(',', '.', $value);
        }
        return \number_format(\floatval($value), 2, '.', '');
    }
}
