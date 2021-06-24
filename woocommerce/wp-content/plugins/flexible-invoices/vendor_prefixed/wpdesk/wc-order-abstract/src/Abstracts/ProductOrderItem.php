<?php

/**
 * Abstracts. Data Container for Product Order Item.
 *
 * @package WPDesk\Library\WPDeskOrder
 */
namespace WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts;

/**
 * Class that stores formatted data from WooCommerce Product Order Item.
 *
 * @package WPDesk\Library\WPDeskOrder\Abstracts
 */
final class ProductOrderItem extends \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\OrderItem
{
    /**
     * @var string
     */
    protected $type = 'line_item';
    /**
     * @var string
     */
    protected $width = '';
    /**
     * @var string
     */
    protected $height = '';
    /**
     * @var string
     */
    protected $weight = '';
    /**
     * @var int
     */
    protected $product_id = 0;
    /**
     * @var string
     */
    protected $sku = '';
    /**
     * @var array
     */
    protected $attributes = [];
    /**
     * @var array
     */
    protected $children = [];
    /**
     * @var int
     */
    private $variation_id = 0;
    /**
     * @param string $width
     */
    public function set_width(string $width)
    {
        $this->width = $width;
    }
    /**
     * @return string
     */
    public function get_width() : string
    {
        return $this->width;
    }
    /**
     * @param string $height
     */
    public function set_height(string $height)
    {
        $this->height = $height;
    }
    /**
     * @return string
     */
    public function get_height() : string
    {
        return $this->height;
    }
    /**
     * @param string $weight
     */
    public function set_weight(string $weight)
    {
        $this->weight = $weight;
    }
    /**
     * @return string
     */
    public function get_weight() : string
    {
        return $this->weight;
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
    /**
     * @param string $sku
     */
    public function set_sku(string $sku)
    {
        $this->sku = $sku;
    }
    /**
     * @return string
     */
    public function get_sku() : string
    {
        return $this->sku;
    }
    /**
     * @param array $attributes
     */
    public function set_attributes(array $attributes)
    {
        $this->attributes = $attributes;
    }
    /**
     * @return array
     */
    public function get_attributes() : array
    {
        return $this->attributes;
    }
    /**
     * @param array $children
     */
    public function set_children(array $children)
    {
        $this->children = $children;
    }
    /**
     * @return array
     */
    public function get_children() : array
    {
        return $this->children;
    }
    /**
     * @param int $variation_id
     */
    public function set_variation_id(int $variation_id)
    {
        $this->variation_id = $variation_id;
    }
    /**
     * @return int
     */
    public function get_variation_id() : int
    {
        return $this->variation_id;
    }
}
