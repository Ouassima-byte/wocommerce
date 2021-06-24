<?php

/**
 * Order. Set Order Items data to the containers.
 *
 * @package WPDesk\Library\WPDeskOrder
 */
namespace WPDeskFIVendor\WPDesk\Library\WPDeskOrder;

use WC_Order;
use WC_Order_Item;
use WC_Order_Item_Coupon;
use WC_Order_Item_Fee;
use WC_Order_Item_Product;
use WC_Order_Item_Shipping;
use WC_Tax;
use WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\CouponOrderItem;
use WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\FeeOrderItem;
use WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\OrderItem;
use WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\ProductOrderItem;
use WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\ShippingOrderItem;
use WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Tax\CalculateTaxFromOrder;
/**
 * Class responsible for formatting the origin WooCommerce Order Item data and set them to the container class.
 *
 * @package WPDesk\Library\WPDeskOrder\Order
 */
class OrderItems
{
    const LINE_ITEM = 'line_item';
    const SHIPPING_ITEM = 'shipping';
    const COUPON_ITEM = 'coupon';
    const FEE_ITEM = 'fee';
    /**
     * @var WC_Order
     */
    private $order;
    /**
     * @var OrderItem[]
     */
    private $items;
    /**
     * @var string
     */
    private $currency;
    /**
     * @var string
     */
    private $currency_symbol;
    /**
     * @var ShippingOrderItem[]
     */
    private $shipping_items = [];
    /**
     * @var ProductOrderItem[]
     */
    private $product_items = [];
    /**
     * @param WC_Order $order WC Order.
     * @param array    $types Item types: ['line_item', 'shipping', 'coupon', 'tax' ].
     */
    public function __construct(\WC_Order $order, array $types)
    {
        if (empty($types)) {
            $types = $this->order_item_types();
        }
        $this->order = $order;
        $this->currency = $order->get_currency();
        $this->currency_symbol = \get_woocommerce_currency_symbol($this->currency);
        $this->items = $this->create_items($order->get_items($types));
    }
    /**
     * @param WC_Order_Item[] $order_items
     *
     * @return OrderItem[]
     */
    private function create_items(array $order_items) : array
    {
        $items = [];
        foreach ($order_items as $order_item) {
            switch ($order_item->get_type()) {
                case self::LINE_ITEM:
                    if ($order_item instanceof \WC_Order_Item_Product) {
                        $items[] = $this->product_items[] = $this->get_product_item($order_item);
                    }
                    break;
                case self::SHIPPING_ITEM:
                    if ($order_item instanceof \WC_Order_Item_Shipping) {
                        $items[] = $this->shipping_items[] = $this->get_shipping_item($order_item);
                    }
                    break;
                case self::COUPON_ITEM:
                    if ($order_item instanceof \WC_Order_Item_Coupon) {
                        $items[] = $this->product_items[] = $this->get_coupon_item($order_item);
                    }
                    break;
                case self::FEE_ITEM:
                    if ($order_item instanceof \WC_Order_Item_Fee) {
                        $items[] = $this->product_items[] = $this->get_fee_item($order_item);
                    }
                    break;
            }
        }
        return $items;
    }
    /**
     * @return OrderItem[]
     */
    public function get_items() : array
    {
        return $this->items;
    }
    /**
     * @param WC_Order_Item_Product $order_item
     *
     * @return ProductOrderItem
     */
    private function get_product_item(\WC_Order_Item_Product $order_item) : \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\ProductOrderItem
    {
        $item = new \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\ProductOrderItem();
        $item->set_item_id($order_item->get_id());
        $item->set_product_id($order_item->get_product_id());
        $item->set_name($order_item->get_name());
        $item->set_qty($order_item->get_quantity());
        $item->set_discount_price($order_item->get_total() - $order_item->get_subtotal() / $order_item->get_quantity());
        $item->set_net_price($order_item->get_total());
        $item->set_net_price_r(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Price::get_rounded_price($item->get_net_price()));
        $item->set_gross_price($this->get_gross_price($order_item->get_total(), $order_item->get_total_tax()));
        $item->set_gross_price_r(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Price::get_rounded_price($item->get_gross_price()));
        $item->set_vat_price($order_item->get_total_tax());
        $item->set_vat_price_r(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Price::get_rounded_price($item->get_vat_price()));
        $item->set_currency_slug($this->currency);
        $item->set_currency_symbol($this->currency_symbol);
        $item->set_meta_data($order_item->get_formatted_meta_data());
        $item->set_variation_id($order_item->get_variation_id());
        $product = \wc_get_product($order_item->get_product_id());
        if ($product) {
            $item->set_attributes($product->get_attributes());
            $item->set_children($product->get_children());
            $item->set_sku($product->get_sku());
            $item->set_tax_class($product->get_tax_class());
            $item->set_width($product->get_width());
            $item->set_height($product->get_height());
            $item->set_weight($product->get_weight());
            $item->set_rate($this->get_product_rate($product));
        } else {
            $item->set_rate($this->calculate_product_rate($order_item));
            $item->set_tax_class($order_item->get_tax_class());
        }
        $taxes = $order_item->get_taxes();
        $taxRate = new \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\GetRateFromTaxTotal($taxes);
        $item->set_rate($taxRate->get_rate());
        $item->set_tax_class($taxRate->get_class());
        return $item;
    }
    /**
     * @param WC_Order_Item_Shipping $order_item
     *
     * @return ShippingOrderItem
     */
    private function get_shipping_item(\WC_Order_Item_Shipping $order_item) : \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\ShippingOrderItem
    {
        $item = new \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\ShippingOrderItem();
        $item->set_item_id($order_item->get_id());
        $item->set_method_id($order_item->get_method_id());
        $item->set_method_title($order_item->get_method_title());
        $item->set_name($order_item->get_name());
        $item->set_qty($order_item->get_quantity());
        $item->set_net_price($order_item->get_total());
        $item->set_net_price_r(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Price::get_rounded_price($item->get_net_price()));
        $item->set_gross_price($this->get_gross_price($order_item->get_total(), $order_item->get_total_tax()));
        $item->set_gross_price_r(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Price::get_rounded_price($item->get_gross_price()));
        $item->set_vat_price($order_item->get_total_tax());
        $item->set_vat_price_r(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Price::get_rounded_price($item->get_vat_price()));
        $item->set_currency_slug($this->currency);
        $item->set_currency_symbol($this->currency_symbol);
        $taxes = $order_item->get_taxes();
        $taxRate = new \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\GetRateFromTaxTotal($taxes);
        $item->set_rate($taxRate->get_rate());
        $item->set_tax_class($taxRate->get_class());
        return $item;
    }
    /**
     * @param WC_Order_Item_Coupon $order_item
     *
     * @return CouponOrderItem
     */
    private function get_coupon_item(\WC_Order_Item_Coupon $order_item) : \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\CouponOrderItem
    {
        $item = new \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\CouponOrderItem();
        $item->set_coupon_code($order_item->get_code());
        $item->set_item_id($order_item->get_id());
        $item->set_name($order_item->get_name());
        $item->set_qty($order_item->get_quantity());
        $item->set_net_price($order_item->get_discount());
        $item->set_net_price_r(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Price::get_rounded_price($item->get_net_price()));
        $item->set_gross_price($order_item->get_discount());
        $item->set_gross_price_r(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Price::get_rounded_price($item->get_gross_price()));
        $item->set_vat_price($order_item->get_discount_tax());
        $item->set_vat_price_r(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Price::get_rounded_price($item->get_vat_price()));
        $item->set_currency_slug($this->currency);
        $item->set_currency_symbol($this->currency_symbol);
        $item->set_rate($this->get_coupon_rate($order_item));
        return $item;
    }
    /**
     * @param WC_Order_Item_Fee $order_item
     *
     * @return FeeOrderItem
     */
    private function get_fee_item(\WC_Order_Item_Fee $order_item) : \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\FeeOrderItem
    {
        $item = new \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\FeeOrderItem();
        $item->set_item_id($order_item->get_id());
        $item->set_name($order_item->get_name());
        $item->set_qty($order_item->get_quantity());
        $item->set_net_price($order_item->get_total());
        $item->set_net_price_r(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Price::get_rounded_price($item->get_net_price()));
        $item->set_gross_price($this->get_gross_price($order_item->get_total(), $order_item->get_total_tax()));
        $item->set_gross_price_r(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Price::get_rounded_price($item->get_gross_price()));
        $item->set_vat_price($order_item->get_total_tax());
        $item->set_vat_price_r(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Price::get_rounded_price($order_item->get_total_tax()));
        $item->set_currency_slug($this->currency);
        $item->set_currency_symbol($this->currency_symbol);
        $taxes = $order_item->get_taxes();
        $taxRate = new \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\GetRateFromTaxTotal($taxes);
        $item->set_rate($taxRate->get_rate());
        $item->set_tax_class($taxRate->get_class());
        return $item;
    }
    /**
     * @param $product
     *
     * @return float
     */
    private function get_product_rate($product)
    {
        $rates = \WC_Tax::find_rates(['country' => $this->order->get_billing_country(), 'tax_class' => $product->get_tax_class()]);
        foreach ($rates as $rate) {
            if (isset($rate['rate'])) {
                return (float) $rate['rate'];
            }
        }
        return 0.0;
    }
    /**
     * @param WC_Order_Item_Product $order_item
     *
     * @return float
     */
    private function calculate_product_rate(\WC_Order_Item_Product $order_item) : float
    {
        if ((float) $order_item['line_subtotal'] > 0) {
            return \round($order_item['line_subtotal_tax'] / $order_item['line_subtotal'] * 100, 1);
        }
        return 0;
    }
    /**
     * @param WC_Order_Item_Coupon $order_item
     *
     * @return float
     */
    private function get_coupon_rate(\WC_Order_Item_Coupon $order_item) : float
    {
        $rates = \WC_Tax::get_rates($order_item->get_tax_class());
        foreach ($rates as $rate) {
            if (isset($rate['rate'])) {
                return (float) $rate['rate'];
            }
        }
        return 0.0;
    }
    /**
     * @return ShippingOrderItem[]
     */
    public function get_shipping_items() : array
    {
        return $this->shipping_items;
    }
    /**
     * @return ProductOrderItem[]
     */
    public function get_product_items() : array
    {
        return $this->product_items;
    }
    /**
     * @param float $price
     * @param float $vat_price
     *
     * @return float
     */
    private function get_gross_price($price, $vat_price) : float
    {
        return \floatval($price) + \floatval($vat_price);
    }
    /**
     * @return string[]
     */
    private function order_item_types() : array
    {
        return array('line_item', 'tax', 'shipping', 'fee', 'coupon');
    }
}
