<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;

/**
 * Helper class for calculating totals for document items.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Helpers
 */
class CalculateTotals
{
    /**
     * @param array $items
     *
     * @return float
     */
    public static function calculate_total_gross(array $items)
    {
        $total_tax_amount = 0.0;
        if (\count($items) > 0 && \is_array($items)) {
            foreach ($items as $item) {
                if (\is_array($item) && isset($item['total_price'])) {
                    $total_tax_amount += (float) $item['total_price'];
                }
            }
        }
        return $total_tax_amount;
    }
    /**
     * @param array $items
     *
     * @return float
     */
    public static function calculate_total_vat(array $items)
    {
        $total_tax_amount = 0.0;
        if (\count($items) > 0 && \is_array($items)) {
            foreach ($items as $item) {
                if (\is_array($item) && isset($item['vat_sum'])) {
                    $total_tax_amount += (float) $item['vat_sum'];
                }
            }
        }
        return $total_tax_amount;
    }
    /**
     * @param array $items
     *
     * @return float
     */
    public static function calculate_total_net(array $items)
    {
        $total_net_price = 0.0;
        if (\count($items) > 0 && \is_array($items)) {
            foreach ($items as $item) {
                if (\is_array($item) && isset($item['net_price_sum'])) {
                    $total_net_price += (float) $item['net_price_sum'];
                }
            }
        }
        return $total_net_price;
    }
    /**
     * @param float $total_gross
     * @param float $total_paid
     *
     * @return float
     */
    public static function calculate_due_price($total_gross, $total_paid)
    {
        $total_gross = self::price_to_float($total_gross);
        $total_paid = self::price_to_float($total_paid);
        return \round($total_gross, 2) - \round($total_paid, 2);
    }
    /**
     * @param float|string $price
     *
     * @return float
     */
    public static function price_to_float($price)
    {
        if (\is_string($price)) {
            $price = \str_replace(',', '.', $price);
            return \floatval($price);
        }
        return $price;
    }
}
