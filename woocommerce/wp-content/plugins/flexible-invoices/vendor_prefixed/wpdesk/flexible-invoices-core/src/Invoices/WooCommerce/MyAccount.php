<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
/**
 * Displays documents on my account.
 */
class MyAccount implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var DocumentFactory
     *
     */
    protected $document_factory;
    /**
     * @var Renderer
     */
    protected $renderer;
    /**
     * @param DocumentFactory $document_factory .
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer)
    {
        $this->document_factory = $document_factory;
        $this->renderer = $renderer;
    }
    /**
     * @return void|null
     */
    public function hooks()
    {
        \add_action('woocommerce_view_order', array($this, 'view_documents'));
    }
    /**
     * @param int $order_id
     *
     * @internal You should not use this directly from another application
     */
    public function view_documents($order_id)
    {
        $creators = $this->document_factory->get_creators();
        foreach ($creators as $creator) {
            if ($creator->is_allowed_for_create()) {
                $order = \wc_get_order($order_id);
                $type = '_' . $creator->get_type();
                $document_id = $order->get_meta($type . '_generated', \true);
                if ($document_id) {
                    $creator = $this->document_factory->get_document_creator($document_id);
                    $document = $creator->get_document();
                    $hash = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Invoice::document_hash($document);
                    echo $this->renderer->render('woocommerce/my-account', ['document' => $document, 'type' => $creator->get_type(), 'name' => $creator->get_name(), 'url' => \admin_url('admin-ajax.php?action=fiw_get_document&id=' . $document->get_id() . '&hash=' . $hash . '&save_file=1')]);
                }
            }
        }
    }
}
