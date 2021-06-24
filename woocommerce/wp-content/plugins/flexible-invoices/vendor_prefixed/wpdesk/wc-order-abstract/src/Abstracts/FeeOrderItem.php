<?php

/**
 * Abstracts. Data Container for Product Order Item.
 *
 * @package WPDesk\Library\WPDeskOrder
 */
namespace WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts;

/**
 * Class that stores formatted data from WooCommerce Product Order Item.
 *
 * @package WPDesk\Library\WPDeskOrder\Abstracts
 */
final class FeeOrderItem extends \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\OrderItem
{
    /**
     * @var string
     */
    protected $type = 'fee';
}
