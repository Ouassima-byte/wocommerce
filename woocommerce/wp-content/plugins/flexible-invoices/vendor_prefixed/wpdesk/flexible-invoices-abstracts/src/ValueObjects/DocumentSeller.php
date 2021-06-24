<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller;
/**
 * Define document seller.
 *
 * @package WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects
 */
class DocumentSeller implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $logo;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $address;
    /**
     * @var string
     */
    private $vat_number;
    /**
     * @var string
     */
    private $bank_name;
    /**
     * @var string
     */
    private $bank_account_number;
    /**
     * @var string
     */
    private $signature_user;
    /**
     * @param string $logo
     * @param string $name
     * @param string $address
     * @param string $vat_number
     * @param string $bank_name
     * @param int    $id
     * @param string $signature_user
     */
    public function __construct($id, $logo, $name, $address, $vat_number, $bank_name, $bank_account_number, $signature_user)
    {
        $this->id = $id;
        $this->logo = $logo;
        $this->name = $name;
        $this->address = $address;
        $this->vat_number = $vat_number;
        $this->bank_name = $bank_name;
        $this->bank_account_number = $bank_account_number;
        $this->signature_user = $signature_user;
    }
    /**
     * @return string
     */
    public function get_logo()
    {
        return $this->logo;
    }
    /**
     * @return string
     */
    public function get_name()
    {
        return $this->name;
    }
    /**
     * @return string
     */
    public function get_address()
    {
        return $this->address;
    }
    /**
     * @return string
     */
    public function get_vat_number()
    {
        return $this->vat_number;
    }
    /**
     * @return string
     */
    public function get_bank_name()
    {
        return $this->bank_name;
    }
    /**
     * @return string
     */
    public function get_bank_account_number()
    {
        return $this->bank_account_number;
    }
    /**
     * @return int
     */
    public function get_id()
    {
        return $this->id;
    }
    /**
     * @return string
     */
    public function get_signature_user()
    {
        return $this->signature_user;
    }
}
