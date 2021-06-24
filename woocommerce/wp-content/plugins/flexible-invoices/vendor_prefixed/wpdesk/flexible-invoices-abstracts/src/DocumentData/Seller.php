<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData;

/**
 * Define document seller.
 *
 * @package WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects
 */
interface Seller
{
    /**
     * @return string
     */
    public function get_logo();
    /**
     * @return string
     */
    public function get_name();
    /**
     * @return string
     */
    public function get_address();
    /**
     * @return string
     */
    public function get_vat_number();
    /**
     * @return string
     */
    public function get_bank_name();
    /**
     * @return string
     */
    public function get_bank_account_number();
    /**
     * @return int
     */
    public function get_id();
    /**
     * @return string
     */
    public function get_signature_user();
}
