<?php

/**
 * Order. Totals
 *
 * @package WPDesk\Library\WPDeskOrder
 */
namespace WPDeskFIVendor\WPDesk\Library\WPDeskOrder;

use WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\OrderItem;
use WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\Totals;
/**
 * This class counting prices and set them to the container class.
 *
 * @package WPDesk\Library\WPDeskOrder\Order
 */
class OrderTotals
{
    /**
     * @var OrderItem[]
     */
    private $order_items;
    /**
     * OrderTotals constructor.
     *
     * @param OrderItems $order_items
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\OrderItems $order_items)
    {
        $this->order_items = $order_items->get_items();
    }
    /**
     * @return Totals
     */
    public function get_totals() : \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\Totals
    {
        $totals = new \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\Totals();
        $total_net = $total_vat = $total_qty = $total_gross = 0;
        if (\count($this->order_items) > 0) {
            foreach ($this->order_items as $item) {
                $total_net += $item->get_net_price();
                $total_vat += $item->get_vat_price();
                $total_gross += $item->get_gross_price();
                $total_qty += $item->get_qty();
                $currency_slug = $item->get_currency_slug();
                $currency_symbol = $item->get_currency_symbol();
            }
            $totals->set_currency_slug($currency_slug);
            $totals->set_currency_symbol($currency_symbol);
            $totals->set_net_price($total_net);
            $totals->set_net_price_r(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Price::get_rounded_price($totals->get_net_price()));
            $totals->set_gross_price($total_gross);
            $totals->set_gross_price_r(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Price::get_rounded_price($totals->get_gross_price()));
            $totals->set_vat_price($total_vat);
            $totals->set_vat_price_r(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Price::get_rounded_price($totals->get_vat_price()));
            $totals->set_qty($total_qty);
        }
        return $totals;
    }
}
