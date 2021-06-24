<?php

/**
 * Abstracts. Totals.
 *
 * @package WPDesk\Library\WPDeskOrder
 */
namespace WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts;

/**
 * Customer.
 *
 * @package WPDesk\Library\WPDeskOrder\Abstracts
 */
final class Customer
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var float
     */
    private $firstname;
    /**
     * @var float
     */
    private $lastname;
    /**
     * @var string
     */
    private $fullname;
    /**
     * @var string
     */
    private $phone;
    /**
     * @var string
     */
    private $company;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $address;
    /**
     * @var string
     */
    private $city;
    /**
     * @var string
     */
    private $post_code;
    /**
     * @var string
     */
    private $country;
    /**
     * @var string
     */
    private $state;
    /**
     * @var string
     */
    private $vat_number;
    /**
     * @var string
     */
    private $note;
    /**
     * @var array
     */
    private $meta = [];
    /**
     * @param int $id
     */
    public function set_id(int $id) : int
    {
        $this->id = $id;
    }
    /**
     * @return int
     */
    public function get_id() : int
    {
        return $this->id;
    }
    /**
     * @param string $firstname
     */
    public function set_firstname(string $firstname)
    {
        $this->firstname = $firstname;
    }
    /**
     * @return string
     */
    public function get_firstname() : string
    {
        return $this->firstname;
    }
    /**
     * @param string $lastname
     */
    public function set_lastname(string $lastname)
    {
        $this->lastname = $lastname;
    }
    /**
     * @return float
     */
    public function get_lastname() : float
    {
        return $this->lastname;
    }
    /**
     * @param string $fullname
     */
    public function set_fullname(string $fullname)
    {
        $this->fullname = $fullname;
    }
    /**
     * @return string
     */
    public function get_fullname() : string
    {
        return $this->fullname;
    }
    /**
     * @param string $phone
     */
    public function set_phone(string $phone)
    {
        $this->phone = $phone;
    }
    /**
     * @return string
     */
    public function get_phone() : string
    {
        return $this->phone;
    }
    /**
     * @param string $company
     */
    public function set_company(string $company)
    {
        $this->company = $company;
    }
    /**
     * @return string
     */
    public function get_company() : string
    {
        return $this->company;
    }
    /**
     * @param string $email
     */
    public function set_email(string $email)
    {
        $this->email = $email;
    }
    /**
     * @return string
     */
    public function get_email() : string
    {
        return $this->email;
    }
    /**
     * @param string $address
     */
    public function set_address(string $address)
    {
        $this->address = $address;
    }
    /**
     * @return string
     */
    public function get_address() : string
    {
        return $this->address;
    }
    /**
     * @param string $city
     */
    public function set_city(string $city)
    {
        $this->city = $city;
    }
    /**
     * @return string
     */
    public function get_city() : string
    {
        return $this->city;
    }
    /**
     * @param string $post_code
     */
    public function set_post_code(string $post_code)
    {
        $this->post_code = $post_code;
    }
    /**
     * @return string
     */
    public function get_post_code() : string
    {
        return $this->post_code;
    }
    /**
     * @param string $country
     */
    public function set_country(string $country)
    {
        $this->country = $country;
    }
    /**
     * @return string
     */
    public function get_country() : string
    {
        return $this->country;
    }
    /**
     * @param string $state
     */
    public function set_state(string $state)
    {
        $this->state = $state;
    }
    /**
     * @return string
     */
    public function get_state() : string
    {
        return $this->state;
    }
    /**
     * @param string $vat_number
     */
    public function set_vat_number(string $vat_number)
    {
        $this->vat_number = $vat_number;
    }
    /**
     * @return string
     */
    public function get_vat_number() : string
    {
        return $this->vat_number;
    }
    /**
     * @param string $note
     */
    public function set_note(string $note)
    {
        $this->note = $note;
    }
    /**
     * @return string
     */
    public function get_note() : string
    {
        return $this->note;
    }
    /**
     * @param array $meta
     */
    public function set_meta(array $meta)
    {
        $this->meta = $meta;
    }
    /**
     * @return array
     */
    public function get_meta() : array
    {
        return $this->meta;
    }
}
