<?php

/**
 * Abstracts. Data Container for Shipping Order Item.
 *
 * @package WPDesk\Library\WPDeskOrder
 */
namespace WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts;

/**
 * Class that stores formatted data from WooCommerce Shipping Order Item.
 *
 * @package WPDesk\Library\WPDeskOrder\Abstracts
 */
final class ShippingOrderItem extends \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\OrderItem
{
    /**
     * @var string
     */
    protected $type = 'shipping';
    /**
     * @var string
     */
    protected $method_id;
    /**
     * @var string
     */
    protected $method_title;
    /**
     * @param string $method_id
     */
    public function set_method_id(string $method_id)
    {
        $this->method_id = $method_id;
    }
    /**
     * @return string
     */
    public function get_method_id() : string
    {
        return $this->method_id;
    }
    /**
     * @param string $method_title
     */
    public function set_method_title(string $method_title)
    {
        $this->method_title = $method_title;
    }
    /**
     * @return string
     */
    public function get_method_title() : string
    {
        return $this->method_title;
    }
}
