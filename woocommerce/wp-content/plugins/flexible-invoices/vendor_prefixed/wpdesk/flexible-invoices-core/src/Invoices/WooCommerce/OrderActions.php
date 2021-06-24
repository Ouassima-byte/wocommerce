<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WC_Order;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Define order actions on Woocommerce Order List.
 */
class OrderActions implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_filter('woocommerce_admin_order_actions', [$this, 'order_status_filter'], 10, 3);
    }
    /**
     *
     * @param array    $actions
     * @param WC_Order $order
     *
     * @return array
     *
     * @internal You should not use this directly from another application
     */
    public function order_status_filter(array $actions, \WC_Order $order) : array
    {
        if ($order->get_meta(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice::META_GENERATED, \true) > 0) {
            $document_id = $order->get_meta(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice::META_GENERATED, \true);
            $actions[] = ['url' => \wp_nonce_url(\admin_url('admin-ajax.php?action=invoice-get-pdf-invoice&id=' . $document_id)), 'name' => \__('View', 'flexible-invoices'), 'action' => 'button view-invoice', 'hint' => \__('View', 'flexible-invoices')];
            $actions[] = ['url' => \wp_nonce_url(\admin_url('admin-ajax.php?action=invoice-get-pdf-invoice&id=' . $document_id . '&save_file=1')), 'name' => \__('Download', 'flexible-invoices'), 'action' => 'button get-invoice', 'hint' => \__('Download', 'flexible-invoices')];
        } else {
            $actions[] = ['url' => \wp_nonce_url(\admin_url('admin-ajax.php?action=woocommere-generate-document&issue_type=action&type=invoice&order_id=' . $order->get_id())), 'name' => \__('Issue Invoice', 'flexible-invoices'), 'action' => 'dashicons generate-invoice'];
        }
        return $actions;
    }
}
