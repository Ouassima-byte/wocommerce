<?php

namespace WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts;

/**
 * Class that stores formatted data from WooCommerce Product Order Item.
 *
 * @package WPDesk\Library\WPDeskOrder\Abstracts
 */
interface OrderItemInterface
{
    /**
     * @param int $item_id
     */
    public function set_item_id(int $item_id);
    /**
     * @return int
     */
    public function get_item_id() : int;
    /**
     * @param string $type
     */
    public function set_type(string $type);
    /**
     * @return string
     */
    public function get_type() : string;
    /**
     * @param string $name
     */
    public function set_name(string $name);
    /**
     * @return string
     */
    public function get_name() : string;
    /**
     * @param int $qty
     */
    public function set_qty(int $qty);
    /**
     * @return int
     */
    public function get_qty() : int;
    /**
     * @param float $discount_price
     */
    public function set_discount_price(float $discount_price);
    /**
     * @return float
     */
    public function get_discount_price() : float;
    /**
     * @param float $net_price
     */
    public function set_net_price(float $net_price);
    /**
     * @return float
     */
    public function get_net_price() : float;
    /**
     * @param float $net_price_r
     */
    public function set_net_price_r(float $net_price_r);
    /**
     * @return float
     */
    public function get_net_price_r() : float;
    /**
     * @param float $gross_price
     */
    public function set_gross_price(float $gross_price);
    /**
     * @return float
     */
    public function get_gross_price() : float;
    /**
     * @param float $gross_price_r
     */
    public function set_gross_price_r(float $gross_price_r);
    /**
     * @return float
     */
    public function get_gross_price_r() : float;
    /**
     * @param float $vat_price
     */
    public function set_vat_price(float $vat_price);
    /**
     * @return float
     */
    public function get_vat_price() : float;
    /**
     * @param float $vat_price_r
     */
    public function set_vat_price_r(float $vat_price_r);
    /**
     * @return float
     */
    public function get_vat_price_r() : float;
    /**
     * @param string $currency_slug
     */
    public function set_currency_slug(string $currency_slug);
    /**
     * @return string
     */
    public function get_currency_slug() : string;
    /**
     * @param string $currency_symbol
     */
    public function set_currency_symbol(string $currency_symbol);
    /**
     * @return string
     */
    public function get_currency_symbol() : string;
    /**
     * @param float $rate
     */
    public function set_rate(float $rate);
    /**
     * @return float
     */
    public function get_rate() : float;
    /**
     * @param array $meta_data
     */
    public function set_meta_data(array $meta_data);
    /**
     * @return array
     */
    public function get_meta_data() : array;
    /**
     * @param string $tax_class
     */
    public function set_tax_class(string $tax_class);
    /**
     * @return string
     */
    public function get_tax_class() : string;
}
