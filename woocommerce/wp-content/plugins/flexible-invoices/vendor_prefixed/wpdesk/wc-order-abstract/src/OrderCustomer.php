<?php

/**
 * Order. Totals
 *
 * @package WPDesk\Library\WPDeskOrder
 */
namespace WPDeskFIVendor\WPDesk\Library\WPDeskOrder;

use WC_Order;
use WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\Customer;
/**
 * Get customer order.
 *
 * @package WPDesk\Library\WPDeskOrder\Order
 */
class OrderCustomer
{
    /**
     * @var WC_Order
     */
    private $order;
    /**
     * @var string
     */
    private $vat_number_key;
    /**
     * @param WC_Order $order
     * @param string   $vat_field_key
     */
    public function __construct(\WC_Order $order, string $vat_field_key = 'company_vat')
    {
        $this->order = $order;
        $this->vat_number_key = $vat_field_key;
    }
    /**
     * @param string $type
     *
     * @return Customer
     */
    public function get(string $type) : \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\Customer
    {
        switch ($type) {
            case 'billing':
                return $this->get_billing_customer();
            case 'shipping':
                return $this->get_shipping_customer();
        }
        return $this->get_billing_customer();
    }
    /**
     * @return Customer
     */
    private function get_billing_customer() : \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\Customer
    {
        $customer = new \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\Customer();
        $customer->set_id($this->order->get_customer_id());
        $customer->set_firstname($this->order->get_billing_first_name());
        $customer->set_lastname($this->order->get_billing_last_name());
        $customer->set_fullname($this->order->get_formatted_billing_full_name());
        $customer->set_phone($this->order->get_billing_phone());
        $customer->set_email($this->order->get_billing_email());
        $customer->set_company($this->order->get_billing_company());
        $customer->set_address($this->order->get_billing_address_1());
        $customer->set_city($this->order->get_billing_city());
        $customer->set_post_code($this->order->get_billing_postcode());
        $customer->set_country($this->order->get_billing_country());
        $customer->set_state($this->order->get_billing_state());
        $customer->set_vat_number($this->get_vat_number());
        $customer->set_note($this->order->get_customer_note());
        return $customer;
    }
    /**
     * @return Customer
     */
    private function get_shipping_customer() : \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\Customer
    {
        $customer = new \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\Customer();
        $customer->set_id($this->order->get_customer_id());
        $customer->set_firstname($this->order->get_shipping_first_name());
        $customer->set_lastname($this->order->get_shipping_last_name());
        $customer->set_fullname($this->order->get_formatted_shipping_full_name());
        $customer->set_company($this->order->get_shipping_company());
        $customer->set_address($this->order->get_shipping_address_1() . ' ' . $this->order->get_shipping_address_2());
        $customer->set_city($this->order->get_shipping_city());
        $customer->set_post_code($this->order->get_shipping_postcode());
        $customer->set_country($this->order->get_shipping_country());
        $customer->set_state($this->order->get_shipping_state());
        $customer->set_note($this->order->get_customer_note());
        return $customer;
    }
    /**
     * @return string
     */
    private function get_vat_number() : string
    {
        $expected_vat_names = ['nip', 'vat_number', 'vat', 'company_vat_number', $this->vat_number_key];
        foreach ($expected_vat_names as $vat_key) {
            if (!empty($this->order->get_meta($vat_key, \true))) {
                return $this->order->get_meta($vat_key, \true);
            }
        }
        return '';
    }
}
