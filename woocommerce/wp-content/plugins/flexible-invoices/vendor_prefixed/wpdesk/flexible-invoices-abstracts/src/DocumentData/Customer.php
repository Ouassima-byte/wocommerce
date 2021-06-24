<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData;

/**
 * Define document customer.
 *
 * @package WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData;
 */
interface Customer
{
    /**
     * @return int
     */
    public function get_id();
    /**
     * @return string
     */
    public function get_name();
    /**
     * @return string
     */
    public function get_street();
    /**
     * @return string
     */
    public function get_street2();
    /**
     * @return string
     */
    public function get_postcode();
    /**
     * @return string
     */
    public function get_city();
    /**
     * @return string
     */
    public function get_vat_number();
    /**
     * @return string
     */
    public function get_country();
    /**
     * @return string
     */
    public function get_phone();
    /**
     * @return string
     */
    public function get_email();
    /**
     * @return string
     */
    public function get_type();
}
