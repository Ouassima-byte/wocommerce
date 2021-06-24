<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items;

interface Item
{
    /**
     * @param string $type
     *
     * @return $this
     */
    public function set_name(string $type) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
    /**
     * @param string $type
     *
     * @return $this
     */
    public function set_type(string $type) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
    /**
     * @return string
     */
    public function get_type() : string;
    /**
     * @param string $unit
     *
     * @return $this
     */
    public function set_unit(string $unit) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
    /**
     * @param int $quantity
     *
     * @return $this
     */
    public function set_qty(int $quantity) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
    /**
     * @param float $net_price
     *
     * @return $this
     */
    public function set_net_price(float $net_price) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
    /**
     * @param float $total_price
     *
     * @return $this
     */
    public function set_gross_price(float $total_price) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
    /**
     * @param float $vat_price
     *
     * @return $this
     */
    public function set_vat_sum(float $vat_price) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
    /**
     * @param float $discount
     *
     * @return $this
     */
    public function set_discount(float $discount) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
    /**
     * @param float $net_price_sum
     *
     * @return $this
     */
    public function set_net_price_sum(float $net_price_sum) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
    /**
     * @param float $vat_rate
     *
     * @return $this
     */
    public function set_vat_rate(float $vat_rate) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
    /**
     * @param float $vat_rate_name
     *
     * @return $this
     */
    public function set_vat_rate_name(string $vat_rate_name) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
    /**
     * @param int $vat_type_index
     *
     * @return $this
     */
    public function set_vat_type_index(int $vat_type_index) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
    /**
     * @param string $sku
     *
     * @return $this
     */
    public function set_sku(string $sku) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
    /**
     * @param array $product_attributes
     *
     * @return $this
     */
    public function set_product_attributes(array $product_attributes) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
    /**
     * @param array $item_meta
     *
     * @return $this
     */
    public function set_meta(array $item_meta) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
    /**
     * @return array
     */
    public function get() : array;
}
