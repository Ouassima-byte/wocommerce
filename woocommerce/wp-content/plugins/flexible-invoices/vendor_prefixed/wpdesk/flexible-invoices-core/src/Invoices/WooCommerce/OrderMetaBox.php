<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WC_Order;
use WP_Post;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
/**
 * Adds a meta box in the order with buttons for generating and displaying the created documents.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce
 */
class OrderMetaBox implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @param DocumentFactory $document_factory
     * @param Renderer        $renderer
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer)
    {
        $this->document_factory = $document_factory;
        $this->renderer = $renderer;
    }
    /**
     * Fires hooks
     */
    public function hooks()
    {
        \add_action('add_meta_boxes', [$this, 'add_meta_box'], 10);
    }
    /**
     * Add meta box for order.
     *
     * @internal You should not use this directly from another application
     */
    public function add_meta_box()
    {
        \add_meta_box('flexible-invoices-woocommerce', \__('Invoice', 'flexible-invoices'), [$this, 'order_meta_box_view'], 'shop_order', 'side', 'core');
    }
    /**
     * @param WP_Post $post
     *
     * @internal You should not use this directly from another application
     */
    public function order_meta_box_view($post)
    {
        $order = new \WC_Order($post->ID);
        $order_has_items = \false;
        if ($order->get_item_count(['line_item', 'shipping', 'fee']) > 0) {
            $order_has_items = \true;
        }
        foreach ($this->document_factory->get_creators() as $creator) {
            $meta_type = '_' . $creator->get_type() . '_generated';
            $document_id = (int) $order->get_meta($meta_type, \true);
            $creator->set_order_id($post->ID);
            $document = $this->document_factory->get_document_creator($document_id)->get_document();
            if ($creator->is_allowed_for_create()) {
                echo $this->renderer->render('woocommerce/document-issued', ['order_has_items' => $order_has_items, 'document_number' => $document->get_formatted_number(), 'document_id' => $document->get_id(), 'order_id' => $order->get_id(), 'button_label' => $creator->get_button_label(), 'type' => $creator->get_type(), 'can_issued' => $creator->is_allowed_for_create(), 'can_edited' => $creator->is_allowed_for_edit()]);
            }
        }
    }
}
