<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Subscriptions integration.
 *
 * @package WPDesk\FlexibleInvoicesPro
 */
class Subscriptions implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    public function hooks()
    {
        \add_filter('wcs_resubscribe_order_meta', array($this, 'wcs_order_meta'), 10, 3);
        \add_filter('wcs_renewal_order_meta', array($this, 'wcs_order_meta'), 10, 3);
    }
    public function wcs_order_meta($meta, $to_order, $from_order)
    {
        foreach ($meta as $key => $meta_item) {
            if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice::META_GENERATED === $meta_item['meta_key']) {
                unset($meta[$key]);
            }
        }
        return $meta;
    }
}
