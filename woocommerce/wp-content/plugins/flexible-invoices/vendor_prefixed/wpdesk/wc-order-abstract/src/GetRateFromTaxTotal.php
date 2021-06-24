<?php

namespace WPDeskFIVendor\WPDesk\Library\WPDeskOrder;

use WC_Tax;
/**
 * Get tax rate form item order.
 *
 * @package WPDesk\Library\WPDeskOrder
 */
class GetRateFromTaxTotal
{
    const INVALID_RATE_ = -1;
    const TAX_HAS_SINGLE_RATE = 1;
    /**
     * @var array
     */
    protected $taxes;
    /**
     * @param array $taxes Taxes.
     */
    public function __construct(array $taxes)
    {
        $this->taxes = $taxes;
    }
    /**
     * Get tax rate class.
     *
     * @return string
     */
    public function get_class() : string
    {
        $class = '';
        if (isset($this->taxes['total'])) {
            $total = $this->remove_empty_rates($this->taxes['total']);
            if (\count($total) === self::TAX_HAS_SINGLE_RATE) {
                $class = \wc_get_tax_class_by_tax_id(\key($total));
                // return string or null ;)
                if (!$class) {
                    return '';
                }
            }
        }
        return $class;
    }
    /**
     * Remove empty rates.
     *
     * @param array $total Tax rates.
     *
     * @return array
     */
    private function remove_empty_rates(array $total) : array
    {
        foreach ($total as $tax_rate_id => $tax_rate) {
            if ($tax_rate === '') {
                unset($total[$tax_rate_id]);
            }
        }
        return $total;
    }
    /**
     * Get rate,
     *
     * @return float
     */
    public function get_rate() : float
    {
        $rate = 0.0;
        if (isset($this->taxes['total'])) {
            $total = $this->remove_empty_rates($this->taxes['total']);
            if (\count($total) === self::TAX_HAS_SINGLE_RATE) {
                $rate = \WC_Tax::get_rate_percent(\key($total));
                $rate = \floatval(\str_replace('%', '', $rate));
            }
        }
        return $rate;
    }
}
