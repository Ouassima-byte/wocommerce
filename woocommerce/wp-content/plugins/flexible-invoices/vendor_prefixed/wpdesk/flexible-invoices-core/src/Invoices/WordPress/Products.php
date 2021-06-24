<?php

/**
 * WordPress. Find products in meta.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore
 */
namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Find products item.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class Products implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    public function hooks()
    {
        if (!\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            \add_action('wp_ajax_fiw_find_products', [$this, 'find_post_meta_products'], 20);
        } else {
            \add_action('wp_ajax_fiw_find_products', [$this, 'find_woocommerce_products'], 20);
        }
    }
    /**
     * Find products saved in meta.
     *
     * @internal You should not use this directly from another application
     */
    public function find_post_meta_products()
    {
        $finded_items = array();
        global $wpdb;
        if (isset($_POST['name']) && isset($_POST['security']) && \wp_verify_nonce($_POST['security'], 'fiw_search_products')) {
            $name = $_POST['name'];
            if (!empty($name)) {
                $results = $wpdb->get_results($wpdb->prepare("SELECT `post_id`, `meta_value` FROM `{$wpdb->postmeta}` WHERE `meta_key` = '_products' AND `meta_value` LIKE %s;", '%' . $wpdb->esc_like($name) . '%'));
                foreach ($results as $row) {
                    $items = \maybe_unserialize($row->meta_value);
                    foreach ($items as $item) {
                        if (\preg_match('/' . $name . '/i', $item['name'])) {
                            $finded_items[] = array('id' => $item['wc_product_id'], 'text' => $item['name'], 'price' => $item['net_price_sum'], 'net_price' => $item['net_price_sum'], 'gross_price' => $item['total_price'], 'tax' => $item['vat_sum'], 'sku' => $item['sku'], 'qty' => $item['quantity'], 'tax_amount' => $item['vat_sum'], 'tax_rate' => $item['vat_type'], 'type' => $item['type'], 'unit' => \__('item', 'flexible-invoice-core'));
                        }
                    }
                    \wp_send_json(array('items' => \array_values($finded_items)));
                }
            }
        }
        \wp_send_json(['items' => []]);
    }
    /**
     * Find WooCommerce products.
     *
     * @internal You should not use this directly from another application
     */
    public function find_woocommerce_products()
    {
        $finded_posts = array();
        global $fiw_client_country;
        $country = !empty($fiw_client_country) ? $fiw_client_country : \get_option('woocommerce_default_country');
        if (isset($_POST['name']) && isset($_POST['security']) && \wp_verify_nonce($_POST['security'], 'fiw_search_products')) {
            $name = $_POST['name'];
            if (!empty($name)) {
                $posts = \get_posts(['post_type' => 'product', 'post_status' => 'publish', 's' => \esc_attr($name)]);
                foreach ($posts as $post) {
                    $product = \wc_get_product($post->ID);
                    $line_price = \floatval($product->get_price());
                    $tax_rates = \WC_Tax::get_rates($product->get_tax_class());
                    $base_tax_rates = \WC_Tax::get_base_tax_rates($product->get_tax_class('unfiltered'));
                    $tax_rate = 0;
                    foreach ($tax_rates as $tax) {
                        $tax_rate = $tax['rate'];
                    }
                    if (\wc_prices_include_tax()) {
                        $remove_taxes = \apply_filters('woocommerce_adjust_non_base_location_prices', \true) ? \WC_Tax::calc_tax($line_price, $base_tax_rates, \true) : \WC_Tax::calc_tax($line_price, $tax_rates, \true);
                        $net_price = \round($line_price - \array_sum($remove_taxes), \wc_get_price_decimals());
                        $gross_price = \round($line_price, \wc_get_price_decimals());
                        $tax_amount = \round(\array_sum($remove_taxes), \wc_get_price_decimals());
                    } else {
                        $add_taxes = \apply_filters('woocommerce_adjust_non_base_location_prices', \true) ? \WC_Tax::calc_tax($line_price, $base_tax_rates, \false) : \WC_Tax::calc_tax($line_price, $tax_rates, \false);
                        $value = \round($line_price + \array_sum($add_taxes), \wc_get_price_decimals());
                        $net_price = \round($line_price, \wc_get_price_decimals());
                        $gross_price = \round($value, \wc_get_price_decimals());
                        $tax_amount = \round(\array_sum($add_taxes), \wc_get_price_decimals());
                    }
                    $finded_posts[$post->ID] = array('id' => $post->post_title, 'text' => $post->post_title, 'price' => $net_price, 'net_price' => $net_price, 'gross_price' => $gross_price, 'tax' => $product->get_tax_class(), 'sku' => $product->get_sku(), 'qty' => 1, 'tax_amount' => $tax_amount, 'tax_rate' => $tax_rate, 'country' => $country);
                }
                \wp_send_json(array('items' => \array_values($finded_posts)));
            }
        }
        \wp_send_json(['items' => []]);
    }
}
