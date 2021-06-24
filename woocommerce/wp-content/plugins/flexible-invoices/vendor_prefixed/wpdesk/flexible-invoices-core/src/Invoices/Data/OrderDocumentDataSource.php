<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data;

use WC_Order;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\OrderItems;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentCustomer;
/**
 * Get document data from order.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Data
 */
class OrderDocumentDataSource extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\AbstractDataSource
{
    const ORDER_PAYMENT_STATUSES = ['processing', 'completed'];
    /**
     * @var WC_Order
     */
    public $order;
    /**
     * @param int      $order_id
     * @param Settings $options_container
     * @param string   $document_type
     *
     * @throws \Exception Throw exception if WooCommerce is not active.
     */
    public function __construct($order_id, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $options_container, $document_type)
    {
        if (!\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            throw new \Exception('Order source cannot be used without WooCommerce!');
        }
        parent::__construct($options_container, $document_type);
        $this->order = new \WC_Order($order_id);
        $wpml_user_lang = \get_post_meta($this->order->get_id(), 'wpml_user_lang', \true);
        if (!empty($wpml_user_lang)) {
            $this->set_wpml_user_lang($wpml_user_lang);
        }
        \do_action('wpml_switch_language', $wpml_user_lang);
    }
    /**
     * @param string       $key
     * @param string|array $data
     */
    private function set_additional_data($key, $data)
    {
        $this->additional_data[$key] = $data;
    }
    /**
     * @return int
     */
    public function get_date_of_sale()
    {
        $_date_sale = \strtotime('NOW');
        if ($this->order->get_date_created()) {
            $_date_sale = $this->order->get_date_created()->getOffsetTimestamp();
        }
        if ($this->settings->get('woocommerce_date_of_sale', 'order_date') === 'order_completed') {
            $completed_date = $this->order->get_date_completed();
            if ($completed_date && $completed_date !== '') {
                $_date_sale = $completed_date->getOffsetTimestamp();
            }
        }
        return $_date_sale;
    }
    /**
     * @return int
     */
    public function get_date_of_pay()
    {
        $pay_date = $this->get_date_of_issue() + 60 * 60 * 24 * \intval($this->settings->get($this->get_document_type() . '_default_due_time'), 0);
        return (int) $pay_date;
    }
    /**
     * @return int
     */
    public function get_date_of_paid()
    {
        $paid_date = $this->order->get_meta('_paid_date', \true);
        if ($paid_date) {
            return \strtotime($paid_date);
        }
        return \current_time('timestamp');
    }
    /**
     * @return int
     */
    public function get_date_of_issue()
    {
        return \current_time('timestamp');
    }
    /**
     * @return Customer
     */
    public function get_customer()
    {
        $billing_company = $this->order->get_billing_company();
        if (empty($billing_company)) {
            $type = 'individual';
            $name = \strip_tags($this->order->get_formatted_billing_full_name());
        } else {
            $type = 'company';
            $name = $billing_company;
        }
        $vat_number = $this->order->get_meta('_billing_vat_number', \true);
        if (empty($vat_number)) {
            $user = new \WP_User($this->order->get_customer_id());
            $vat_number = isset($user->vat_number) ? $user->vat_number : '';
        }
        $id = $this->order->get_customer_id();
        $street = $this->order->get_billing_address_1();
        $street2 = $this->order->get_billing_address_2();
        $postcode = $this->order->get_billing_postcode();
        $city = $this->order->get_billing_city();
        $nip = $vat_number;
        $country = $this->order->get_billing_country();
        $phone = $this->order->get_billing_phone();
        $email = $this->order->get_billing_email();
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentCustomer($id, $name, $street, $postcode, $city, $nip, $country, $phone, $email, $type, $street2);
    }
    /**
     * @return string
     */
    public function get_customer_filter_field()
    {
        return $this->get_customer()->get_name();
    }
    /**
     * @return string
     */
    public function get_currency()
    {
        return $this->order->get_currency();
    }
    /**
     * @return float
     */
    public function get_discount()
    {
        return $this->order->get_total_discount();
    }
    /**
     * @return int
     */
    public function get_order_id()
    {
        return $this->order->get_id();
    }
    /**
     * @return array
     */
    public function get_items()
    {
        $order_items = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\OrderItems($this->order);
        return $order_items->get_items();
    }
    /**
     * @return string
     */
    public function get_payment_method()
    {
        return $this->order->get_payment_method();
    }
    /**
     * @return string
     */
    public function get_payment_method_name()
    {
        return $this->order->get_payment_method_title();
    }
    /**
     * Get notes from settings.
     * @return string
     */
    public function get_notes()
    {
        return \__($this->settings->get($this->get_document_type() . '_notes'), 'flexible-invoices');
    }
    /**
     * @return float
     */
    public function get_total_gross()
    {
        return $this->order->get_total();
    }
    /**
     * @return float
     */
    public function get_total_net()
    {
        return $this->order->get_total() - $this->order->get_total_tax();
    }
    /**
     * @return float
     */
    public function get_total_paid()
    {
        if ($this->get_payment_status() === self::ORDER_PAYMENT_PAID_STATUS) {
            return $this->order->get_total();
        } else {
            return $this->total_paid;
        }
    }
    /**
     * @return float
     */
    public function get_total_tax()
    {
        return $this->order->get_total_tax();
    }
    /**
     * @return string
     */
    public function get_payment_status()
    {
        $payment_method = $this->order->get_meta('_payment_method', \true);
        if ($payment_method !== 'cod' && \in_array($this->order->get_status(), self::ORDER_PAYMENT_STATUSES) && $this->settings->get('woocommerce_auto_paid_status') === 'yes') {
            return self::ORDER_PAYMENT_PAID_STATUS;
        } else {
            if ($payment_method == 'cod' && \in_array($this->order->get_status(), array('completed')) && $this->settings->get('woocommerce_auto_paid_status') === 'yes') {
                return self::ORDER_PAYMENT_PAID_STATUS;
            } else {
                return self::ORDER_PAYMENT_TO_PAY_STATUS;
            }
        }
    }
    /**
     * @return int
     */
    public function get_show_order_number()
    {
        if ($this->settings->get('woocommerce_add_order_id', 'no') === 'yes') {
            return 1;
        }
        return 0;
    }
    /**
     * @return string
     */
    public function get_user_lang()
    {
        return \strtolower($this->user_lang);
    }
}
