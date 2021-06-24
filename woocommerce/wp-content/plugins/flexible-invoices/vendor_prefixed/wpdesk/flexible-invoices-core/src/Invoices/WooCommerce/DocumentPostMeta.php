<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\MetaPostContainer;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Save custom post meta for WooCommerce.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce
 */
class DocumentPostMeta implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Fires hooks
     */
    public function hooks()
    {
        \add_action('fi/core/document/save', [$this, 'before_save_action'], 80, 2);
    }
    /**
     *
     * @param Document          $document
     * @param MetaPostContainer $meta
     *
     * @internal You should not use this directly from another application
     */
    public function before_save_action(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\MetaPostContainer $meta)
    {
        if (!empty($_POST)) {
            $add_order_id = 0;
            if (isset($_POST['add_order_id'])) {
                $add_order_id = $_POST['add_order_id'];
            }
            $meta->set('add_order_id', $add_order_id);
        }
    }
}
