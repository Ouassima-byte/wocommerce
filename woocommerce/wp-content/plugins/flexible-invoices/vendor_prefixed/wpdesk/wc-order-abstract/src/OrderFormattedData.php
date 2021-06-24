<?php

/**
 * Order. Extends WC_Order class.
 *
 * @package WPDesk\Library\WPDeskOrder
 */
namespace WPDeskFIVendor\WPDesk\Library\WPDeskOrder;

use Exception;
use WC_Order;
use WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\OrderItem;
/**
 * This class extends the WC_Order class.
 * It has two additional methods which return formatted item data and totals.
 *
 * @package WPDesk\Library\WPDeskOrder\Order
 */
class OrderFormattedData
{
    /**
     * @var OrderItems
     */
    private $order_items;
    /**
     * @var WC_Order
     */
    private $order;
    /**
     * @param WC_Order $order
     */
    public function __construct(\WC_Order $order)
    {
        $this->order = $order;
    }
    /**
     * @param array $types Array of item types which will be returned from the order. ['line_item', 'shipping', 'coupon', 'tax' ]
     *
     * @return OrderItems
     */
    public function get_order_items($types = []) : \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\OrderItems
    {
        $this->order_items = new \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\OrderItems($this->order, $types);
        return $this->order_items;
    }
    /**
     * @return Abstracts\Totals
     * @throws Exception Throw exception if formatted_order_items is not initialized.
     */
    public function get_order_totals() : \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\Totals
    {
        if (!$this->order_items) {
            throw new \Exception('Fire formatted_order_items() first');
        }
        return (new \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\OrderTotals($this->order_items))->get_totals();
    }
    /**
     * @param string $type
     * @param string $vat_field_key
     *
     * @return Abstracts\Customer
     */
    public function get_customer($type = 'billing', string $vat_field_key = '') : \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\Customer
    {
        return (new \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\OrderCustomer($this->order, $vat_field_key))->get($type);
    }
    /**
     * @return WC_Order
     */
    public function get_order() : \WC_Order
    {
        return $this->order;
    }
}
