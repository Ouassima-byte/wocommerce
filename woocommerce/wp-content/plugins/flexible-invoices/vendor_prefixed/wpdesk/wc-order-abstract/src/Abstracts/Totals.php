<?php

/**
 * Abstracts. Totals.
 *
 * @package WPDesk\Library\WPDeskOrder
 */
namespace WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts;

/**
 * This class stores totals of all items.
 *
 * @package WPDesk\Library\WPDeskOrder\Abstracts
 */
final class Totals
{
    /**
     * @var int
     */
    private $qty;
    /**
     * @var float
     */
    private $net_price;
    /**
     * @var float
     */
    private $net_price_r;
    /**
     * @var float
     */
    private $vat_price;
    /**
     * @var float
     */
    private $vat_price_r;
    /**
     * @var float
     */
    private $gross_price;
    /**
     * @var float
     */
    private $gross_price_r;
    /**
     * @var string
     */
    private $currency_slug;
    /**
     * @var string
     */
    private $currency_symbol;
    /**
     * @param int $qty
     */
    public function set_qty(int $qty)
    {
        $this->qty = $qty;
    }
    /**
     * @return int
     */
    public function get_qty() : int
    {
        return $this->qty;
    }
    /**
     * @param float $net_price
     */
    public function set_net_price(float $net_price)
    {
        $this->net_price = $net_price;
    }
    /**
     * @return float
     */
    public function get_net_price() : float
    {
        return $this->net_price;
    }
    /**
     * @param float $net_price_r
     */
    public function set_net_price_r(float $net_price_r)
    {
        $this->net_price_r = $net_price_r;
    }
    /**
     * @return float
     */
    public function get_net_price_r() : float
    {
        return $this->net_price_r;
    }
    /**
     * @param float $vat_price
     */
    public function set_vat_price(float $vat_price)
    {
        $this->vat_price = $vat_price;
    }
    /**
     * @return float
     */
    public function get_vat_price() : float
    {
        return $this->vat_price;
    }
    /**
     * @param float $vat_price_r
     */
    public function set_vat_price_r(float $vat_price_r)
    {
        $this->vat_price_r = $vat_price_r;
    }
    /**
     * @return float
     */
    public function get_vat_price_r() : float
    {
        return $this->vat_price_r;
    }
    /**
     * @param float $gross_price
     */
    public function set_gross_price(float $gross_price)
    {
        $this->gross_price = $gross_price;
    }
    /**
     * @return float
     */
    public function get_gross_price() : float
    {
        return $this->gross_price;
    }
    /**
     * @param float $gross_price_r
     */
    public function set_gross_price_r(float $gross_price_r)
    {
        $this->gross_price_r = $gross_price_r;
    }
    /**
     * @return float
     */
    public function get_gross_price_r() : float
    {
        return $this->gross_price_r;
    }
    /**
     * @param string $currency_slug
     */
    public function set_currency_slug(string $currency_slug)
    {
        $this->currency_slug = $currency_slug;
    }
    /**
     * @return string
     */
    public function get_currency_slug() : string
    {
        return $this->currency_slug;
    }
    /**
     * @param string $currency_symbol
     */
    public function set_currency_symbol(string $currency_symbol)
    {
        $this->currency_symbol = $currency_symbol;
    }
    /**
     * @return string
     */
    public function get_currency_symbol() : string
    {
        return $this->currency_symbol;
    }
}
