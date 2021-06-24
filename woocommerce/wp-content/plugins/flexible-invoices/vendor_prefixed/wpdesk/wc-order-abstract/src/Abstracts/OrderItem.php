<?php

/**
 * Abstracts. Data Container for Order Item.
 *
 * @package WPDesk\Library\WPDeskOrder
 */
namespace WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts;

/**
 * Class that stores formatted data from WooCommerce Product Order Item.
 *
 * @package WPDesk\Library\WPDeskOrder\Abstracts
 */
abstract class OrderItem implements \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\OrderItemInterface
{
    /**
     * @var int
     */
    protected $item_id;
    /**
     * @var string
     */
    protected $type = 'line_item';
    /**
     * @var string
     */
    protected $name = '';
    /**
     * @var int
     */
    protected $qty = 1;
    /**
     * @var float
     */
    protected $discount_price = 0.0;
    /**
     * @var float
     */
    protected $net_price = 0.0;
    /**
     * @var float
     */
    protected $net_price_r = 0.0;
    /**
     * @var float
     */
    protected $gross_price = 0.0;
    /**
     * @var float
     */
    protected $gross_price_r = 0.0;
    /**
     * @var float
     */
    protected $vat_price = 0.0;
    /**
     * Rounded vat price.
     *
     * @var float
     */
    protected $vat_price_r = 0.0;
    /**
     * @var string
     */
    protected $currency_slug = '';
    /**
     * @var string
     */
    protected $currency_symbol = '';
    /**
     * @var float
     */
    protected $rate = 0.0;
    /**
     * @var array
     */
    protected $meta_data = [];
    /**
     * @var string
     */
    protected $tax_class = '';
    /**
     * @var int
     */
    protected $product_id = 0;
    /**
     * @param int $item_id
     */
    public function set_item_id(int $item_id)
    {
        $this->item_id = $item_id;
    }
    /**
     * @return int
     */
    public function get_item_id() : int
    {
        return $this->item_id;
    }
    /**
     * @param string $type
     */
    public function set_type(string $type)
    {
        $this->type = $type;
    }
    /**
     * @return string
     */
    public function get_type() : string
    {
        return $this->type;
    }
    /**
     * @param string $name
     */
    public function set_name(string $name)
    {
        $this->name = $name;
    }
    /**
     * @return string
     */
    public function get_name() : string
    {
        return $this->name;
    }
    /**
     * @param int $qty
     */
    public function set_qty(int $qty)
    {
        $this->qty = $qty;
    }
    /**
     * @return int
     */
    public function get_qty() : int
    {
        return $this->qty;
    }
    /**
     * @param float $discount_price
     */
    public function set_discount_price(float $discount_price)
    {
        $this->discount_price = $discount_price;
    }
    /**
     * @return float
     */
    public function get_discount_price() : float
    {
        return $this->discount_price;
    }
    /**
     * @param float $net_price
     */
    public function set_net_price(float $net_price)
    {
        $this->net_price = $net_price;
    }
    /**
     * @return float
     */
    public function get_net_price() : float
    {
        return $this->net_price;
    }
    /**
     * @param float $net_price_r
     */
    public function set_net_price_r(float $net_price_r)
    {
        $this->net_price_r = $net_price_r;
    }
    /**
     * @return float
     */
    public function get_net_price_r() : float
    {
        return $this->net_price_r;
    }
    /**
     * @param float $gross_price
     */
    public function set_gross_price(float $gross_price)
    {
        $this->gross_price = $gross_price;
    }
    /**
     * @return float
     */
    public function get_gross_price() : float
    {
        return $this->gross_price;
    }
    /**
     * @param float $gross_price_r
     */
    public function set_gross_price_r(float $gross_price_r)
    {
        $this->gross_price_r = $gross_price_r;
    }
    /**
     * @return float
     */
    public function get_gross_price_r() : float
    {
        return $this->gross_price_r;
    }
    /**
     * @param float $vat_price
     */
    public function set_vat_price(float $vat_price)
    {
        $this->vat_price = $vat_price;
    }
    /**
     * @return float
     */
    public function get_vat_price() : float
    {
        return $this->vat_price;
    }
    /**
     * @param float $vat_price_r
     */
    public function set_vat_price_r(float $vat_price_r)
    {
        $this->vat_price_r = $vat_price_r;
    }
    /**
     * @return float
     */
    public function get_vat_price_r() : float
    {
        return $this->vat_price_r;
    }
    /**
     * @param string $currency_slug
     */
    public function set_currency_slug(string $currency_slug)
    {
        $this->currency_slug = $currency_slug;
    }
    /**
     * @return string
     */
    public function get_currency_slug() : string
    {
        return $this->currency_slug;
    }
    /**
     * @param string $currency_symbol
     */
    public function set_currency_symbol(string $currency_symbol)
    {
        $this->currency_symbol = $currency_symbol;
    }
    /**
     * @return string
     */
    public function get_currency_symbol() : string
    {
        return $this->currency_symbol;
    }
    /**
     * @param float $rate
     */
    public function set_rate(float $rate)
    {
        $this->rate = $rate;
    }
    /**
     * @return float
     */
    public function get_rate() : float
    {
        return $this->rate;
    }
    /**
     * @param array $meta_data
     */
    public function set_meta_data(array $meta_data)
    {
        $this->meta_data = $meta_data;
    }
    /**
     * @return array
     */
    public function get_meta_data() : array
    {
        return $this->meta_data;
    }
    /**
     * @param string $tax_class
     */
    public function set_tax_class(string $tax_class)
    {
        $this->tax_class = $tax_class;
    }
    /**
     * @return string
     */
    public function get_tax_class() : string
    {
        return $this->tax_class;
    }
    /**
     * @param int $product_id
     */
    public function set_product_id(int $product_id)
    {
        $this->product_id = $product_id;
    }
    /**
     * @return int
     */
    public function get_product_id() : int
    {
        return $this->product_id;
    }
}
