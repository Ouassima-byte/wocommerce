<?php

/**
 * Abstracts. Data Container for Coupon Order Item.
 *
 * @package WPDesk\Library\WPDeskOrder
 */
namespace WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts;

/**
 * Class that stores formatted data from WooCommerce Coupon Order Item.
 *
 * @package WPDesk\Library\WPDeskOrder\Abstracts
 */
final class CouponOrderItem extends \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\OrderItem
{
    /**
     * @var string
     */
    protected $type = 'coupon';
    /**
     * @var string
     */
    protected $coupon_code;
    /**
     * @param string $coupon_code
     */
    public function set_coupon_code(string $coupon_code)
    {
        $this->coupon_code = $coupon_code;
    }
    /**
     * @return string
     */
    public function get_coupon_code() : string
    {
        return $this->coupon_code;
    }
}
