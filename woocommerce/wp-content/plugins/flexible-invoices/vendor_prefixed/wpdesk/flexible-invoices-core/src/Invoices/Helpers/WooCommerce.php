<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;

/**
 * WooCommerce helpers functions.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Helpers
 */
class WooCommerce
{
    /**
     * @return bool
     */
    public static function is_active()
    {
        global $woocommerce;
        return $woocommerce instanceof \WooCommerce;
    }
    /**
     * @param \WC_Order $order
     * @param int       $item_id
     * @param false     $convert_to_array
     *
     * @return array
     */
    public static function get_order_item_meta_data(\WC_Order $order, $item_id, $convert_to_array = \false)
    {
        if ($convert_to_array) {
            $metas = $order->get_item($item_id)->get_meta_data();
            $ret = array();
            foreach ($metas as $meta) {
                $ret[] = array('id' => $meta->id, 'meta_id' => $meta->id, 'meta_key' => $meta->key, 'meta_value' => $meta->value);
            }
            return $ret;
        } else {
            return $order->get_item($item_id)->get_meta_data();
        }
    }
    /**
     * @param int    $order_id
     * @param string $meta_key
     * @param string $meta_value
     */
    public static function update_order_meta($order_id, $meta_key, $meta_value)
    {
        $order = \wc_get_order($order_id);
        $order->update_meta_data($meta_key, $meta_value);
        $order->save();
    }
    /**
     * Gets an array of countries in the EU.
     *
     * @param string $type Type of countries to retrieve. Blank for EU member countries. eu_vat for EU VAT countries.
     *
     * @return string[]
     */
    public static function get_european_union_countries($type = '')
    {
        $countries = array('AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HU', 'HR', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK');
        if ('eu_vat' === $type) {
            $countries[] = 'MC';
            $countries[] = 'IM';
        }
        /**
         * Filters European Union countries.
         *
         * @param string $countries Countries.
         * @param string $type      Type of countries to retrieve. Blank for EU member countries. eu_vat for EU VAT countries.
         *
         * @since 3.0.0
         */
        return \apply_filters('woocommerce_european_union_countries', $countries, $type);
    }
}
