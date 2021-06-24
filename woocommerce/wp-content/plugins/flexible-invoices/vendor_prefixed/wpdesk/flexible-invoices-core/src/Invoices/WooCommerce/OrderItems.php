<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WC_Order;
use WC_Product_Attribute;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\ItemFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\WooProductItem;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\OrderItem;
use WPDeskFIVendor\WPDesk\Library\WPDeskOrder\OrderFormattedData;
/**
 * Get Order items for document.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Abstracts\Items
 */
class OrderItems
{
    const WC_COUPON_ITEM_TYPE = 'coupon';
    /**
     * @var string
     */
    private $unit;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var array
     */
    private $items = [];
    public function __construct(\WC_Order $order)
    {
        $this->order = $order;
        $this->unit = \__('item', 'flexible-invoices');
        $this->settings = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings();
        $this->create_invoice_items();
    }
    /**
     * @param OrderItem $item
     *
     * @return bool
     */
    private function should_skip_item(\WPDeskFIVendor\WPDesk\Library\WPDeskOrder\Abstracts\OrderItem $item) : bool
    {
        if ($item->get_type() === self::WC_COUPON_ITEM_TYPE) {
            return \true;
        }
        if ($this->settings->get('woocommerce_zero_product') === 'yes' && $item->get_net_price() === 0.0) {
            return \true;
        }
        /**
         * Filter for skipping an item passed from a WooCommerce order.
         *
         * @param bool      $skip Should skip item?
         * @param OrderItem $item Order item object.
         */
        return (bool) \apply_filters('fi/core/woocommerce/document/item/skip', \false, $item);
    }
    private function create_invoice_items()
    {
        $order_items = (new \WPDeskFIVendor\WPDesk\Library\WPDeskOrder\OrderFormattedData($this->order))->get_order_items()->get_items();
        foreach ($order_items as $order_item) {
            if ($this->should_skip_item($order_item)) {
                continue;
            }
            $items_factory = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\ItemFactory($order_item->get_type());
            $item = $items_factory->get_item();
            /**
             * Filter item title.
             *
             * @param string $title Item title.
             */
            $title = \apply_filters('fi/core/woocommerce/document/item/title', $order_item->get_name());
            $tax_rate = $this->get_vat_rate_from_settings_from_value($order_item->get_rate());
            $rate_name = empty($tax_rate) ? $tax_rate . '%' : $tax_rate['name'];
            $item->set_name($title)->set_net_price($order_item->get_net_price() / $order_item->get_qty())->set_net_price_sum($order_item->get_net_price())->set_discount($order_item->get_discount_price())->set_unit($this->unit)->set_gross_price($order_item->get_gross_price())->set_vat_rate($order_item->get_rate())->set_vat_rate_name($rate_name)->set_vat_sum($order_item->get_vat_price())->set_qty($order_item->get_qty())->set_unit($this->unit)->set_meta($order_item->get_meta_data());
            if (\is_a($item, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Items\WooProductItem::class)) {
                if ('yes' === $this->settings->get('woocommerce_get_sku')) {
                    $item->set_sku($order_item->get_sku());
                }
                $item->set_wc_order_item_id($order_item->get_item_id())->set_wc_product_id($order_item->get_product_id())->set_product_attributes($this->get_product_attributes($order_item->get_product_id()));
                $show_meta = $this->settings->get('woocommerce_add_variant_info') === 'yes';
                /**
                 * Filter for show meta data keys & values after title.
                 *
                 * @param bool $show_meta Should show meta?
                 */
                $show_meta_in_title = \apply_filters('fi/core/woocommerce/document/item/show_meta', $show_meta);
                if ($show_meta_in_title) {
                    $variation_data = $this->get_variation_info($order_item->get_meta_data());
                    $item->set_name($order_item->get_name() . $variation_data);
                }
            }
            $this->items[] = $item->get();
        }
    }
    /**
     * @return array
     */
    private function get_vat_types() : array
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
    /**
     * @return array
     */
    public function get_items() : array
    {
        return $this->items;
    }
    /**
     * @param int $id
     *
     * @return array
     */
    private function get_product_attributes(int $id) : array
    {
        $parsed_attributes = [];
        $product = \wc_get_product($id);
        if ($product) {
            $attributes = $product->get_attributes();
            foreach ($attributes as $attribute_key => $attribute) {
                if ($attribute instanceof \WC_Product_Attribute) {
                    $parsed_attributes[$attribute_key] = ['key' => $attribute_key, 'id' => $attribute->get_id(), 'values' => $attribute->get_options(), 'name' => $attribute->get_name(), 'visible' => $attribute->get_visible()];
                }
            }
        }
        return $parsed_attributes;
    }
    /**
     * @param array $meta_data
     *
     * @return string
     */
    private function get_variation_info(array $meta_data) : string
    {
        $variation_data = [];
        foreach ($meta_data as $meta) {
            $variation_data[] = $meta->key . ': ' . $meta->value;
        }
        if (!empty($variation_data)) {
            return ' (' . \implode(', ', $variation_data) . ')';
        }
        return '';
    }
}
