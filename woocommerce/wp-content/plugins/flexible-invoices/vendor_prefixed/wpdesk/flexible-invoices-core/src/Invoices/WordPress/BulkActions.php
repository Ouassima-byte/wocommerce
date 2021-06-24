<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Register bulk actions.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class BulkActions implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    const PAID_BULK_ACTION_ID = 'set_as_payed';
    const PAID_BULK_CONFIRM_ID = 'bulk_set_as_payed';
    const SEND_BULK_CONFIRM_ID = 'bulk_send_email';
    public function hooks()
    {
        \add_filter('bulk_actions-edit-inspire_invoice', array($this, 'add_bulk_option'));
        \add_filter('handle_bulk_actions-edit-inspire_invoice', array($this, 'set_bulk_actions_handler'), 10, 3);
        \add_action('admin_notices', array($this, 'bulk_notice'));
    }
    /**
     * @param array $actions
     *
     * @return array
     *
     * @internal You should not use this directly from another application
     */
    public function add_bulk_option($actions)
    {
        if (isset($actions['edit'])) {
            unset($actions['edit']);
        }
        $actions[self::PAID_BULK_ACTION_ID] = \__('Paid', 'flexible-invoices');
        return $actions;
    }
    /**
     * @param string $redirect_to
     * @param string $do_action
     * @param array  $post_ids
     *
     * @return string
     *
     * @internal You should not use this directly from another application
     */
    public function set_bulk_actions_handler($redirect_to, $do_action, $post_ids)
    {
        if ($do_action === self::PAID_BULK_ACTION_ID) {
            foreach ($post_ids as $post_id) {
                \update_post_meta($post_id, '_payment_status', 'paid');
            }
            $redirect_to = \add_query_arg(self::PAID_BULK_CONFIRM_ID, \count($post_ids), $redirect_to);
            return $redirect_to;
        }
        return $redirect_to;
    }
    /**
     * Show notice after invoices are defined as paid.
     *
     * @internal You should not use this directly from another application
     */
    public function bulk_notice()
    {
        if (!empty($_REQUEST[self::PAID_BULK_CONFIRM_ID])) {
            $invoices_count = \intval($_REQUEST[self::PAID_BULK_CONFIRM_ID]);
            \printf('<div id="message" class="updated notice"><p>' . \_n('%s invoice marked as paid.', '%s invoices marked as paid.', $invoices_count, 'flexible-invoices') . '</p></div>', $invoices_count);
        }
        if (!empty($_REQUEST[self::SEND_BULK_CONFIRM_ID])) {
            $invoices_count = \intval($_REQUEST[self::SEND_BULK_CONFIRM_ID]);
            print '<div id="message" class="updated notice"><p>' . \__('Invoices was sent.', 'flexible-invoices') . '</p></div>';
        }
    }
}
