<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WC_Order;
use WC_Order_Item;
use WC_Order_Item_Product;
use WC_Product;
use WC_Product_Attribute;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
/**
 * Get Order items for document.
 *
 * @todo    Need refactor in next time.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Abstracts\Items
 */
class WCOrderItems
{
    /**
     * @var WC_Order
     */
    private $order;
    /**
     * @var string
     */
    private $unit_translate;
    /**
     * @var array
     */
    private $products = [];
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var int
     */
    private $discount = 0;
    public function __construct(\WC_Order $order)
    {
        $this->order = $order;
        $this->unit_translate = \__('item', 'flexible-invoices');
        $this->settings = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings();
        $this->set_product_items();
        $this->set_shipping_items();
        $this->set_fee_items();
        $this->set_discount_items();
    }
    /**
     * @return array
     */
    public function get_vat_types()
    {
        $rates = array();
        $inspire_invoices_tax = \get_option('inspire_invoices_tax', array());
        $index = 0;
        foreach ($inspire_invoices_tax as $tax) {
            $rates[] = array('index' => $index, 'rate' => $tax['rate'], 'name' => $tax['name']);
            $index++;
        }
        return (array) \apply_filters('inspire_invoices_vat_types', $rates);
    }
    private function get_vat_rate_from_settings_from_value($value)
    {
        $vatTypes = $this->get_vat_types();
        foreach ($vatTypes as $vatType) {
            if ($value == $vatType['rate']) {
                return $vatType;
            }
        }
        return 0;
    }
    public function get_items()
    {
        return $this->products;
    }
    /**
     * @param WC_Order_Item $item
     *
     * @return array
     */
    private function get_product_attributes(\WC_Order_Item $item)
    {
        $parsed_attributes = [];
        if ($item instanceof \WC_Order_Item_Product) {
            $product = \wc_get_product($item->get_product_id());
            $attributes = $product->get_attributes();
            foreach ($attributes as $attribute_key => $attribute) {
                if ($attribute instanceof \WC_Product_Attribute) {
                    $parsed_attributes[$attribute_key] = ['key' => $attribute_key, 'id' => $attribute->get_id(), 'values' => $attribute->get_options(), 'name' => $attribute->get_name(), 'visible' => $attribute->get_visible()];
                }
            }
        }
        return $parsed_attributes;
    }
    private function set_product_items()
    {
        $items = $this->order->get_items();
        foreach ($items as $item_id => $item) {
            if ($this->settings->get('woocommerce_zero_product') === 'yes') {
                if ((float) $item['line_total'] === 0.0) {
                    continue;
                }
            }
            if ($item['line_subtotal'] > 0) {
                $vatRateValue = \round($item['line_subtotal_tax'] / $item['line_subtotal'] * 100, 1);
            } else {
                $vatRateValue = 0;
            }
            $vatRate = $this->get_vat_rate_from_settings_from_value($vatRateValue);
            $productObject = new \WC_Product($item['product_id']);
            $variation_data = '';
            if ($this->settings->get('woocommerce_add_variant_info') === 'yes' && ($metadata = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::get_order_item_meta_data($this->order, $item_id, \true))) {
                foreach ($metadata as $meta) {
                    if (\strpos($meta['meta_key'], '_') === 0) {
                        continue;
                    }
                    if (\is_serialized($meta['meta_value'])) {
                        continue;
                    }
                    if (\taxonomy_exists(\wc_sanitize_taxonomy_name($meta['meta_key']))) {
                        $term = \get_term_by('slug', $meta['meta_value'], \wc_sanitize_taxonomy_name($meta['meta_key']));
                        $meta['meta_key'] = \wc_attribute_label(\wc_sanitize_taxonomy_name($meta['meta_key']));
                        $meta['meta_value'] = isset($term->name) ? $term->name : $meta['meta_value'];
                    } else {
                        $meta['meta_key'] = \apply_filters('woocommerce_attribute_label', \wc_attribute_label($meta['meta_key'], $productObject), $meta['meta_key']);
                    }
                    $variation_data .= $meta['meta_key'] . ': ' . $meta['meta_value'] . ', ';
                }
            }
            if ($variation_data != '') {
                $variation_data = ' (' . \trim(\trim($variation_data), ',') . ')';
            }
            $sku = '';
            if ('yes' === $this->settings->get('woocommerce_get_sku')) {
                $sku = $productObject->get_sku();
                if (isset($item['variation_id'])) {
                    $product_variation = \wc_get_product($item['variation_id']);
                    if ($product_variation) {
                        $sku = $product_variation->get_sku();
                    }
                }
            }
            $this->products[] = \apply_filters('fi/core/order/data/product', array('type' => 'product', 'name' => $item['name'] . $variation_data, 'unit' => $this->unit_translate, 'quantity' => $item['qty'], 'net_price' => $item['line_total'] / \intval($item['qty']), 'discount' => $item['line_subtotal'] - $item['line_total'] / \intval($item['qty']), 'net_price_discount' => $item['line_total'] / \intval($item['qty']), 'net_price_sum' => $item['line_total'], 'vat_rate' => $vatRateValue, 'vat_sum' => $item['line_tax'], 'total_price' => $item['line_tax'] + $item['line_total'], 'vat_type' => $vatRateValue, 'vat_type_name' => empty($vatRate) ? $vatRateValue . '%' : $vatRate['name'], 'vat_type_index' => empty($vatRate) ? 0 : $vatRate['index'], 'wc_item_type' => $item['type'], 'wc_order_item_id' => $item['item_meta'], 'wc_product_id' => $item['product_id'], 'wc_variation_id' => $item['variation_id'], 'sku' => $sku, 'product_attributes' => $this->get_product_attributes($item)), $this->order);
        }
    }
    private function set_shipping_items()
    {
        $items = $this->order->get_items('shipping');
        foreach ($items as $item_id => $item) {
            if ($this->settings->get('woocommerce_zero_product') === 'yes') {
                if ((float) $item['total'] === 0.0) {
                    continue;
                }
            }
            if ($item['total'] > 0) {
                $vatRateValue = \round($item['total_tax'] / $item['total'] * 100, 1);
            } else {
                $vatRateValue = 0;
            }
            $vatRate = $this->get_vat_rate_from_settings_from_value($vatRateValue);
            $product = array('type' => 'shipping', 'name' => $item['name'], 'unit' => $this->unit_translate, 'quantity' => 1, 'net_price' => $item['total'] / 1, 'net_price_sum' => $item['total'], 'vat_rate' => $vatRateValue, 'vat_sum' => $item['total_tax'], 'total_price' => $item['total_tax'] + $item['total'], 'vat_type' => $vatRateValue, 'vat_type_name' => empty($vatRate) ? $vatRateValue . '%' : $vatRate['name'], 'vat_type_index' => empty($vatRate) ? 0 : $vatRate['index']);
            $this->products[] = $product;
        }
    }
    private function set_fee_items()
    {
        $items = $this->order->get_items('fee');
        foreach ($items as $item_id => $item) {
            if ($this->settings->get('woocommerce_zero_product') === 'yes') {
                if ((float) $item['line_total'] === 0.0) {
                    continue;
                }
            }
            if ($item['line_total'] > 0) {
                $vatRateValue = \round($item['line_tax'] / $item['line_total'] * 100, 1);
            } else {
                $vatRateValue = 0;
            }
            $vatRate = $this->get_vat_rate_from_settings_from_value($vatRateValue);
            $product = array('type' => 'fee', 'name' => $item['name'], 'unit' => $this->unit_translate, 'quantity' => 1, 'net_price' => $item['line_total'] / 1, 'discount' => 0, 'net_price_discount' => $item['line_total'] / 1, 'net_price_sum' => $item['line_total'], 'vat_rate' => $vatRateValue, 'vat_sum' => $item['line_tax'], 'total_price' => $item['line_tax'] + $item['line_total'], 'vat_type' => $vatRateValue, 'vat_type_name' => empty($vatRate) ? $vatRateValue . '%' : $vatRate['name'], 'vat_type_index' => empty($vatRate) ? 0 : $vatRate['index'], 'wc_item_type' => $item['type'], 'wc_order_item_id' => $item['item_meta']);
            $this->products[] = $product;
        }
    }
    private function set_discount_items()
    {
        if (isset($this->order->order_discount)) {
            $this->discount = (double) $this->order->order_discount;
        }
        if ($this->discount !== 0) {
            $this->products[] = array('type' => 'discount', 'name' => \__('Discount', 'flexible-invoices'), 'unit' => \__('service', 'flexible-invoices'), 'vat_type' => 0, 'vat_type_name' => '0%', 'quantity' => 1, 'vat_sum' => 0, 'net_price' => -$this->discount, 'net_price_sum' => -$this->discount, 'total_price' => -$this->discount);
        }
    }
}
