<?php

/**
 * Order. Formatting.
 *
 * @package WPDesk\Library\WPDeskOrder
 */
namespace WPDeskFIVendor\WPDesk\Library\WPDeskOrder;

/**
 * @package WPDesk\Library\WPDeskOrder\Order
 */
class Price
{
    /**
     * @param float $price
     *
     * @return string
     */
    public static function get_rounded_price(float $price) : string
    {
        return \number_format($price, 2, '.', '');
    }
}
