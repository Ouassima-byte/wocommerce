<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items;

abstract class DocumentItem implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
{
    const TYPE = 'item';
    protected $data = ['type' => 'item', 'name' => '', 'unit' => 'item', 'quantity' => 1, 'net_price' => 0.0, 'discount' => 0.0, 'net_price_sum' => 0.0, 'vat_rate' => 0.0, 'vat_sum' => 0.0, 'vat_type' => 0, 'vat_type_name' => '0%', 'vat_type_index' => 0, 'total_price' => 0.0, 'sku' => '', 'product_attributes' => [], 'item_meta' => []];
    public function __construct()
    {
        $this->set_type(static::TYPE);
    }
    /**
     * @param string $type
     *
     * @return $this
     */
    public function set_name(string $type) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $this->data['name'] = $type;
        return $this;
    }
    /**
     * @param string $type
     *
     * @return $this
     */
    public function set_type(string $type) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $this->data['type'] = $type;
        return $this;
    }
    /**
     * @return string
     */
    public function get_type() : string
    {
        return $this->data['type'];
    }
    /**
     * @param string $unit
     *
     * @return $this
     */
    public function set_unit(string $unit) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $this->data['unit'] = $unit;
        return $this;
    }
    /**
     * @param int $quantity
     *
     * @return $this
     */
    public function set_qty(int $quantity) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $this->data['quantity'] = $quantity;
        return $this;
    }
    /**
     * @param float $net_price
     *
     * @return $this
     */
    public function set_net_price(float $net_price) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $this->data['net_price'] = $net_price;
        return $this;
    }
    /**
     * @param float $total_price
     *
     * @return $this
     */
    public function set_gross_price(float $total_price) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $this->data['total_price'] = $total_price;
        return $this;
    }
    /**
     * @param float $vat_price
     *
     * @return $this
     */
    public function set_vat_sum(float $vat_price) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $this->data['vat_sum'] = $vat_price;
        return $this;
    }
    /**
     * @param float $discount
     *
     * @return $this
     */
    public function set_discount(float $discount) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $this->data['discount'] = $discount;
        return $this;
    }
    /**
     * @param float $net_price_sum
     *
     * @return $this
     */
    public function set_net_price_sum(float $net_price_sum) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $this->data['net_price_sum'] = $net_price_sum;
        return $this;
    }
    /**
     * @param float $vat_rate
     *
     * @return $this
     */
    public function set_vat_rate(float $vat_rate) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $this->data['vat_rate'] = $vat_rate;
        $this->data['vat_type'] = $vat_rate;
        $this->data['vat_type_name'] = $vat_rate . '%';
        return $this;
    }
    /**
     * @param string $vat_rate_name
     *
     * @return $this
     */
    public function set_vat_rate_name(string $vat_rate_name) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $this->data['vat_type_name'] = $vat_rate_name;
        return $this;
    }
    /**
     * @param int $vat_type_index
     *
     * @return $this
     */
    public function set_vat_type_index(int $vat_type_index) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $this->data['vat_type_index'] = $vat_type_index;
        return $this;
    }
    /**
     * @param string $sku
     *
     * @return $this
     */
    public function set_sku(string $sku) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $this->data['sku'] = $sku;
        return $this;
    }
    /**
     * @param array $product_attributes
     *
     * @return $this
     */
    public function set_product_attributes(array $product_attributes) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $this->data['product_attributes'] = $product_attributes;
        return $this;
    }
    /**
     * @param array $item_meta
     *
     * @return $this
     */
    public function set_meta(array $item_meta) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $this->data['item_meta'] = $item_meta;
        return $this;
    }
    /**
     * @return array
     */
    public function get() : array
    {
        return $this->data;
    }
}
