<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Deleting the relationship between a document and an order.
 *
 * When a document is deleted through the WordPress panel,
 * the relation is deleted and the document can be reissued for the order.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce
 */
class OrderDocumentRelation implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @param DocumentFactory $document_factory
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory)
    {
        $this->document_factory = $document_factory;
    }
    /**
     * Fires hooks
     */
    public function hooks()
    {
        \add_action('before_delete_post', [$this, 'remove_order_relation']);
    }
    /**
     * When document are deleting remove relation for order.
     *
     * @param $id
     *
     * @internal You should not use this directly from another application
     */
    public function remove_order_relation($id)
    {
        global $post_type;
        $document = $this->document_factory->get_document_creator($id);
        $type = '_' . $document->get_type();
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME === $post_type) {
            $correction = (int) \get_post_meta($id, $type, \true);
            if ($correction === 1) {
                $order_id = \get_post_meta($id, '_wc_order_id', \true);
                \delete_post_meta($order_id, $type . '_corrections');
                \delete_post_meta($order_id, $type . '_generated');
            } else {
                $order_id = \get_post_meta($id, '_wc_order_id', \true);
                \delete_post_meta($order_id, $type . '_generated');
            }
        }
    }
}
