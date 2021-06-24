<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller;
/**
 * This interface defines methods for different data sources.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Data
 */
interface SourceData
{
    /**
     * @return string
     */
    public function get_document_type();
    /**
     * @return array
     */
    public function get_items();
    /**
     * @return Seller
     */
    public function get_seller();
    /**
     * @return Customer
     */
    public function get_customer();
    /**
     * @return string
     */
    public function get_formatted_number();
    /**
     * @return string
     */
    public function get_currency();
    /**
     * @return string
     */
    public function get_currency_symbol();
    /**
     * @return string
     */
    public function get_payment_method();
    /**
     * @return string
     */
    public function get_payment_method_name();
    /**
     * @return string
     */
    public function get_notes();
    /**
     * @return string
     */
    public function get_user_lang();
    /**
     * @return int
     */
    public function get_id();
    /**
     * @return int
     */
    public function get_order_id();
    /**
     * @return float
     */
    public function get_total_paid();
    /**
     * @return string
     */
    public function get_payment_status();
    /**
     * @return int
     */
    public function get_number();
    /**
     * @return int
     */
    public function get_date_of_sale();
    /**
     * @return int
     */
    public function get_date_of_issue();
    /**
     * @return int
     */
    public function get_date_of_pay();
    /**
     * @return int
     */
    public function get_date_of_paid();
    /**
     * @return float
     */
    public function get_total_tax();
    /**
     * @return float
     */
    public function get_total_net();
    /**
     * @return float
     */
    public function get_total_gross();
    /**
     * @return float
     */
    public function get_tax();
    /**
     * @return float
     */
    public function get_discount();
    /**
     * @return string
     */
    public function get_customer_filter_field();
    /**
     * @param string $key
     *
     * @return string|array
     */
    public function get_additional_data($key, $source);
    /**
     * @return int
     */
    public function get_show_order_number();
    /**
     * @return int
     */
    public function get_is_correction();
    /**
     * @return int
     */
    public function get_corrected_id();
}
