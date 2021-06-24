<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory;
/**
 * Abstraction of Document Type Declaration.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Documents
 */
abstract class AbstractDocument implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document
{
    const DOCUMENT_TYPE = 'invoice';
    /**
     * @var int
     */
    private $number;
    /**
     * @var string
     */
    private $formatted_number;
    /**
     * @var string
     */
    private $currency;
    /**
     * @var string
     */
    private $payment_method_name;
    /**
     * @var string
     */
    private $payment_method = 'topay';
    /**
     * @var string
     */
    private $notes;
    /**
     * @var string
     */
    private $user_lang;
    /**
     * @var int
     */
    private $id = 0;
    /**
     * @var int
     */
    private $order_id = 0;
    /**
     * @var float
     */
    private $total_paid;
    /**
     * @var string
     */
    private $payment_status;
    /**
     * @var array
     */
    private $items;
    /**
     * @var int
     */
    private $date_of_sale;
    /**
     * @var int
     */
    private $date_of_issue;
    /**
     * @var int
     */
    private $date_of_pay;
    /**
     * @var int
     */
    private $paid_date;
    /**
     * @var float
     */
    private $total_tax;
    /**
     * @var float
     */
    private $total_net;
    /**
     * @var float
     */
    private $total_gross;
    /**
     * @var float
     */
    private $tax;
    /**
     * @var Seller
     */
    private $owner;
    /**
     * @var Customer
     */
    private $client;
    /**
     * @var string
     */
    private $client_filtered_name;
    /**
     * @var float
     */
    private $discount;
    /**
     * @var int
     */
    private $post_id;
    /**
     * @var array
     */
    private $additional_data;
    /**
     * @var string
     */
    private $show_order_number = 0;
    /**
     * @var int
     */
    private $corrected_id = 0;
    /**
     * @var string
     */
    private $is_correction = 0;
    /**
     * @param string $value
     */
    public function set_formatted_number($value)
    {
        $this->formatted_number = $value;
    }
    /**
     * @return string
     */
    public function get_formatted_number()
    {
        return $this->formatted_number;
    }
    /**
     * @param string $value
     */
    public function set_currency($value)
    {
        $this->currency = $value;
    }
    /**
     * @return string
     */
    public function get_currency()
    {
        return $this->currency;
    }
    /**
     * @param string $value
     */
    public function set_currency_symbol($value)
    {
        $this->currency = $value;
    }
    /**
     * @return string
     */
    public function get_currency_symbol()
    {
        return $this->currency;
    }
    /**
     * @param string $value
     */
    public function set_payment_method($value)
    {
        $this->payment_method = $value;
    }
    /**
     * @return string
     */
    public function get_payment_method()
    {
        return $this->payment_method;
    }
    /**
     * @param string $value
     */
    public function set_payment_method_name($value)
    {
        $this->payment_method_name = $value;
    }
    /**
     * @return string
     */
    public function get_payment_method_name()
    {
        return $this->payment_method_name;
    }
    /**
     * @param string $value
     */
    public function set_notes($value)
    {
        $this->notes = $value;
    }
    /**
     * @return string
     */
    public function get_notes()
    {
        return $this->notes;
    }
    /**
     * @param $value
     */
    public function set_user_lang($value)
    {
        $this->user_lang = $value;
    }
    /**
     * @return string
     */
    public function get_user_lang()
    {
        return $this->user_lang;
    }
    /**
     * @param int $id
     */
    public function set_id($id)
    {
        $this->id = $id;
    }
    /**
     * @return int
     */
    public function get_id()
    {
        return $this->id;
    }
    /**
     * @param string $value
     */
    public function set_total_paid($value)
    {
        $this->total_paid = $value;
    }
    /**
     * @return float
     */
    public function get_total_paid()
    {
        return $this->total_paid;
    }
    /**
     * @param string $value
     */
    public function set_payment_status($value)
    {
        $this->payment_status = $value;
    }
    /**
     * @return string
     */
    public function get_payment_status()
    {
        return $this->payment_status;
    }
    /**
     * @param array $items
     */
    public function set_items($items)
    {
        $this->items = $items;
    }
    /**
     * @return array
     */
    public function get_items()
    {
        return $this->items;
    }
    /**
     * @param int $number
     */
    public function set_number($number)
    {
        $this->number = $number;
    }
    /**
     * @return int
     */
    public function get_number()
    {
        return $this->number;
    }
    /**
     * @param int $value
     */
    public function set_date_of_sale($value)
    {
        $this->date_of_sale = $value;
    }
    /**
     * @return int
     */
    public function get_date_of_sale()
    {
        return $this->date_of_sale;
    }
    /**
     * @param int $value
     */
    public function set_date_of_issue($value)
    {
        $this->date_of_issue = $value;
    }
    /**
     * @return int
     */
    public function get_date_of_issue()
    {
        return $this->date_of_issue;
    }
    /**
     * @param int $value
     */
    public function set_date_of_pay($value)
    {
        $this->date_of_pay = $value;
    }
    /**
     * @return int
     */
    public function get_date_of_pay()
    {
        return $this->date_of_pay;
    }
    /**
     * @param int $value
     */
    public function set_date_of_paid($value)
    {
        $this->paid_date = $value;
    }
    /**
     * @return int
     */
    public function get_date_of_paid()
    {
        return $this->paid_date;
    }
    /**
     * @param float $value
     */
    public function set_total_tax($value)
    {
        $this->total_tax = $value;
    }
    /**
     * @return float
     */
    public function get_total_tax()
    {
        return $this->total_tax;
    }
    /**
     * @param float $value
     */
    public function set_total_net($value)
    {
        $this->total_net = $value;
    }
    /**
     * @return float
     */
    public function get_total_net()
    {
        return $this->total_net;
    }
    /**
     * @param float $value
     */
    public function set_total_gross($value)
    {
        $this->total_gross = $value;
    }
    /**
     * @return float
     */
    public function get_total_gross()
    {
        return $this->total_gross;
    }
    /**
     * @param $value
     */
    public function set_tax($value)
    {
        $this->tax = $value;
    }
    /**
     * @return float
     */
    public function get_tax()
    {
        return $this->tax;
    }
    /**
     * @param Seller $seller
     */
    public function set_seller(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller $seller)
    {
        $this->owner = $seller;
    }
    /**
     * @return Seller
     */
    public function get_seller()
    {
        return $this->owner;
    }
    /**
     * @param Customer $customer
     */
    public function set_customer(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer $customer)
    {
        $this->client = $customer;
    }
    /**
     * @return Customer
     */
    public function get_customer()
    {
        return $this->client;
    }
    /**
     * @param float $value
     */
    public function set_discount($value)
    {
        $this->discount = $value;
    }
    /**
     * @return float
     */
    public function get_discount()
    {
        return $this->discount;
    }
    /**
     * @param string $customer_name
     */
    public function set_customer_filter_field($customer_name)
    {
        $this->client_filtered_name = $customer_name;
    }
    /**
     * @return string
     */
    public function get_customer_filter_field()
    {
        return $this->client_filtered_name;
    }
    /**
     * @param string $value
     */
    public function set_show_order_number($value)
    {
        $this->show_order_number = $value;
    }
    /**
     * @return bool
     */
    public function get_show_order_number()
    {
        return $this->show_order_number;
    }
    /**
     * @param int $id
     */
    public function set_order_id($id)
    {
        $this->order_id = $id;
    }
    /**
     * @return int
     */
    public function get_order_id()
    {
        return $this->order_id;
    }
    /**
     * @param int $id
     */
    public function set_corrected_id($id)
    {
        $this->corrected_id = $id;
    }
    /**
     * @return int
     */
    public function get_corrected_id()
    {
        return $this->corrected_id;
    }
    /**
     * @param string $key
     * @param string $value
     */
    public function set_additional_data($key, $value)
    {
        $this->additional_data[$key] = $value;
    }
    /**
     * @param string $key
     * @param string $source
     *
     * @return string
     */
    public function get_additional_data($key, $source)
    {
        if ($source === \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory::META_SOURCE) {
            return \get_post_meta($this->get_id(), $key, \true);
        }
        return isset($_POST[$key]) ? $_POST[$key] : '';
    }
    /**
     * @param bool $is_correction
     */
    public function set_is_correction($is_correction)
    {
        $this->is_correction = $is_correction;
    }
    /**
     * @return int
     */
    public function get_is_correction()
    {
        return $this->is_correction;
    }
}
