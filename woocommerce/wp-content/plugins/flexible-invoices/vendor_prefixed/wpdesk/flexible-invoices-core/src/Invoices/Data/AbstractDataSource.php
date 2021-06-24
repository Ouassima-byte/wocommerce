<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentCustomer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentSeller;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
/**
 * Abstraction for data source.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Data
 */
abstract class AbstractDataSource implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\SourceData
{
    const ORDER_PAYMENT_PAID_STATUS = 'paid';
    const ORDER_PAYMENT_TO_PAY_STATUS = 'topay';
    const DOCUMENT_TYPE = 'invoice';
    /**
     * @var Settings
     */
    protected $settings;
    /**
     * @var string
     */
    protected $document_type;
    /**
     * @var string
     */
    protected $formatted_number;
    /**
     * @var float
     */
    protected $total_price = 0;
    /**
     * @var string
     */
    protected $currency;
    /**
     * @var string
     */
    protected $payment_method_name;
    /**
     * @var string
     */
    protected $payment_method;
    /**
     * @var string
     */
    protected $notes = '';
    /**
     * @var string
     */
    protected $user_lang = 'en';
    /**
     * @var int
     */
    protected $id;
    /**
     * @var int
     */
    protected $order_id;
    /**
     * @var int
     */
    protected $corrected_id;
    /**
     * @var float
     */
    protected $total_paid = 0.0;
    /**
     * @var string
     */
    protected $payment_status;
    /**
     * @var array
     */
    protected $items = [];
    /**
     * @var int
     */
    protected $number;
    /**
     * @var int
     */
    protected $date_of_sale;
    /**
     * @var int
     */
    protected $date_of_issue;
    /**
     * @var int
     */
    protected $date_of_pay;
    /**
     * @var int
     */
    protected $paid_date;
    /**
     * @var float
     */
    protected $total_tax = 0.0;
    /**
     * @var float
     */
    protected $total_net = 0.0;
    /**
     * @var float
     */
    protected $total_gross = 0.0;
    /**
     * @var float
     */
    protected $tax;
    /**
     * @var Seller
     */
    protected $seller;
    /**
     * @var Customer
     */
    protected $customer;
    /**
     * @var string
     */
    protected $customer_filtered_name = '';
    /**
     * @var float
     */
    protected $discount = 0.0;
    /**
     * @var int
     */
    protected $post_id = 0;
    /**
     * @var array
     */
    protected $additional_data = [];
    /**
     * @param Settings $settings
     * @param string   $document_type
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings, $document_type)
    {
        $this->settings = $settings;
        $this->document_type = $document_type;
        $this->set_wpml_user_lang(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::get_active_lang());
    }
    /**
     * @param string $value
     */
    public function set_wpml_user_lang($value)
    {
        $this->user_lang = $value;
    }
    /**
     * @return string
     */
    public function get_document_type()
    {
        return $this->document_type;
    }
    /**
     * @return int
     */
    public function get_number()
    {
        return $this->number;
    }
    /**
     * @return string
     */
    public function get_formatted_number()
    {
        return $this->formatted_number;
    }
    /**
     * @return string
     */
    public function get_currency()
    {
        return $this->currency;
    }
    /**
     * @return string
     */
    public function get_currency_symbol()
    {
        return $this->currency;
    }
    /**
     * @return string
     */
    public function get_payment_method()
    {
        return $this->payment_method;
    }
    /**
     * @return string
     */
    public function get_payment_method_name()
    {
        return $this->payment_method_name;
    }
    /**
     * @return string
     */
    public function get_notes()
    {
        return $this->settings->get($this->get_document_type() . '_notes');
    }
    /**
     * @return string
     */
    public function get_user_lang()
    {
        return $this->user_lang;
    }
    /**
     * @return int
     */
    public function get_id()
    {
        return $this->id;
    }
    /**
     * @return int
     */
    public function get_order_id()
    {
        return $this->order_id;
    }
    /**
     * @return float
     */
    public function get_total_paid()
    {
        return $this->total_paid;
    }
    /**
     * @return string
     */
    public function get_payment_status()
    {
        return $this->payment_status;
    }
    /**
     * @return int
     */
    public function get_date_of_sale()
    {
        return $this->date_of_sale;
    }
    /**
     * @return int
     */
    public function get_date_of_issue()
    {
        return $this->date_of_issue;
    }
    /**
     * @return int
     */
    public function get_date_of_pay()
    {
        return $this->date_of_pay;
    }
    /**
     * @return int
     */
    public function get_date_of_paid()
    {
        return $this->paid_date;
    }
    /**
     * @return float
     */
    public function get_total_tax()
    {
        return $this->total_tax;
    }
    /**
     * @return float
     */
    public function get_total_net()
    {
        return $this->total_net;
    }
    /**
     * @return float
     */
    public function get_total_gross()
    {
        return $this->total_gross;
    }
    /**
     * @return float
     */
    public function get_tax()
    {
        return $this->tax;
    }
    /**
     * @return float
     */
    public function get_discount()
    {
        return $this->discount;
    }
    /**
     * @return string
     */
    public function get_customer_filter_field()
    {
        return $this->customer_filtered_name;
    }
    /**
     * @param string $key
     *
     * @return string|array
     */
    public function get_additional_data($key, $source)
    {
        if (!empty($key)) {
            return isset($this->additional_data[$key]) ? $this->additional_data[$key] : '';
        }
        return '';
    }
    /**
     * @return Customer
     */
    public function get_customer()
    {
        $id = isset($this->customer['id']) ? $this->customer['id'] : '';
        $name = isset($this->customer['name']) ? $this->customer['name'] : '';
        $street = isset($this->customer['street']) ? $this->customer['street'] : '';
        $street2 = isset($this->customer['street2']) ? $this->customer['street2'] : '';
        $postcode = isset($this->customer['postcode']) ? $this->customer['postcode'] : '';
        $city = isset($this->customer['city']) ? $this->customer['city'] : '';
        $vat_number = isset($this->customer['nip']) ? $this->customer['nip'] : '';
        $country = isset($this->customer['country']) ? $this->customer['country'] : '';
        $phone = isset($this->customer['phone']) ? $this->customer['phone'] : '';
        $email = isset($this->customer['email']) ? $this->customer['email'] : '';
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentCustomer($id, $name, $street, $postcode, $city, $vat_number, $country, $phone, $email, 'individual', $street2);
    }
    /**
     * @return Seller
     */
    public function get_seller()
    {
        $name = $this->settings->has('company_name') ? $this->settings->get('company_name') : '';
        $address = $this->settings->has('company_address') ? $this->settings->get('company_address') : '';
        $nip = $this->settings->has('company_nip') ? $this->settings->get('company_nip') : '';
        $bank_name = $this->settings->has('bank_name') ? $this->settings->get('bank_name') : '';
        $bank_account = $this->settings->has('account_number') ? $this->settings->get('account_number') : '';
        $logo = $this->settings->has('company_logo') ? $this->settings->get('company_logo') : '';
        $signature_user = $this->settings->has('signature_user') ? $this->settings->get('signature_user') : '';
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentSeller(0, $logo, $name, $address, $nip, $bank_name, $bank_account, $signature_user);
    }
    /**
     * @return array
     */
    public function get_items()
    {
        return $this->items;
    }
    /**
     * @return int
     */
    public function get_show_order_number()
    {
        return 0;
    }
    /**
     * @return int
     */
    public function get_corrected_id()
    {
        return $this->corrected_id;
    }
    /**
     * @return int
     */
    public function get_is_correction()
    {
        return 0;
    }
}
