<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents;

use Exception;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\DiscountItem;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\FeeItem;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\ItemFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\ProductItem;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\ShippingItem;
class CreateDocumentItem
{
    const ITEM_TYPES = [\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\ProductItem::TYPE, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\ShippingItem::TYPE, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\DiscountItem::TYPE, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\FeeItem::TYPE];
    /**
     * @var string
     */
    private $type;
    /**
     * @throws Exception
     */
    public function __construct(string $type)
    {
        if (!\in_array($type, self::ITEM_TYPES)) {
            throw new \Exception('Unknown item type! Choose from: ' . \implode(', ', self::ITEM_TYPES));
        }
        $this->type = $type;
    }
    /**
     * @return Item
     */
    private function get_item_type() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        return (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\ItemFactory($this->type))->get_item();
    }
    /**
     * @param float $net_price
     * @param float $vat_rate
     * @param int   $qty
     *
     * @return Item
     */
    public function net_price(float $net_price, float $vat_rate, int $qty = 1) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $item = $this->get_item_type();
        $item->set_qty($qty);
        $item->set_net_price($net_price);
        $item->set_net_price_sum($net_price * $qty);
        $item->set_vat_rate($vat_rate);
        $vat_sum = $vat_rate * ($net_price / 100);
        $item->set_vat_sum($vat_sum * $qty);
        $item->set_gross_price(($net_price + $vat_sum) * $qty);
        return $item;
    }
    /**
     * @param float $gross_price
     * @param float $vat_rate
     * @param int   $qty
     *
     * @return Item
     */
    public function gross_price(float $gross_price, float $vat_rate, int $qty = 1) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\Item
    {
        $item = $this->get_item_type();
        $item->set_qty($qty);
        $item->set_gross_price($gross_price);
        $item->set_vat_rate($vat_rate);
        $vatDivisor = 1 + $vat_rate / 100;
        $net_price = $gross_price / $vatDivisor;
        $vat_sum = $gross_price - $net_price;
        $item->set_vat_sum($vat_sum * $qty);
        $item->set_net_price($net_price);
        $item->set_net_price_sum($net_price * $qty);
        return $item;
    }
}
