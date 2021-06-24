<?php

/**
 * Invoice. Add custom meta boxes.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore
 */
namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WP_Post;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\DocumentDecorator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
/**
 * Register custom meta boxes.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class RegisterMetaBoxes implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @var SettingsStrategy
     */
    private $strategy;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @param SettingsStrategy $strategy
     * @param DocumentFactory  $document_factory
     * @param Renderer         $renderer
     * @param Settings         $settings
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings)
    {
        $this->document_factory = $document_factory;
        $this->strategy = $strategy;
        $this->renderer = $renderer;
        $this->settings = $settings;
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_action('add_meta_boxes', array($this, 'register_meta_boxes'), 1, 2);
    }
    /**
     * @param string  $post_type
     * @param WP_Post $post
     *
     * @internal You should not use this directly from another application
     */
    public function register_meta_boxes($post_type, $post = null)
    {
        if ($post_type === \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME) {
            $document = $this->document_factory->get_document_creator($post->ID)->get_document();
            $invoice = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\DocumentDecorator($document, $this->strategy);
            \add_meta_box('seller', \__('Seller', 'flexible-invoices'), array($this, 'owner_metabox_callback'), \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME, 'normal', 'high', array('invoice' => $invoice));
            \add_meta_box('customer', \__('Customer', 'flexible-invoices'), array($this, 'client_metabox_callback'), \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME, 'normal', 'high', array('invoice' => $invoice));
            \add_meta_box('products', \__('Products', 'flexible-invoices'), array($this, 'products_metabox_callback'), \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME, 'normal', 'high', array('invoice' => $invoice));
            \add_meta_box('payment', \__('Payments and other info', 'flexible-invoices'), array($this, 'payment_metabox_callback'), \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME, 'normal', 'high', array('invoice' => $invoice));
            \add_meta_box('options', \__('Dates and actions', 'flexible-invoices'), array($this, 'options_metabox_callback'), \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME, 'side', 'high', array('invoice' => $invoice));
        }
    }
    /**
     * @param WP_Post $post
     * @param array   $args
     */
    public function owner_metabox_callback($post, $args)
    {
        /**
         * @var Document $invoice
         */
        $invoice = $args['args']['invoice'];
        echo $this->renderer->render('invoice_edit/owner_metabox', array('invoice' => $args['args']['invoice'], 'signature_user' => $this->settings->get('signature_user'), 'plugin' => $this, 'owner' => $invoice->get_seller(), 'post' => $post));
    }
    /**
     * @param WP_Post $post
     * @param array   $args
     */
    public function options_metabox_callback($post, $args)
    {
        /**
         * @var Document $invoice
         */
        $invoice = $args['args']['invoice'];
        echo $this->renderer->render('invoice_edit/options_metabox', array('invoice' => $invoice, 'plugin' => $this, 'post' => $post));
    }
    /**
     * @param WP_Post $post
     * @param array   $args
     */
    public function client_metabox_callback($post, $args)
    {
        \wp_nonce_field('flexible_invoices_nonce', 'flexible_invoices_nonce');
        /**
         * @var Document $invoice
         */
        $invoice = $args['args']['invoice'];
        echo $this->renderer->render('invoice_edit/client_metabox', array('invoice' => $args['args']['invoice'], 'client' => $invoice->get_customer(), 'plugin' => $invoice, 'post' => $post));
    }
    /**
     * @param WP_Post $post
     * @param array   $args
     */
    public function products_metabox_callback($post, $args)
    {
        /**
         * @var Document $invoice
         */
        $invoice = $args['args']['invoice'];
        echo $this->renderer->render('invoice_edit/products_metabox', array('invoice' => $invoice, 'vat_types' => $this->strategy->get_taxes(), 'plugin' => $this, 'post' => $post));
    }
    /**
     * @param WP_Post $post
     * @param array   $args
     */
    public function payment_metabox_callback($post, $args)
    {
        /**
         * @var Document $invoice
         */
        $invoice = $args['args']['invoice'];
        echo $this->renderer->render('invoice_edit/payment_metabox', array('invoice' => $invoice, 'plugin' => $this, 'payment_statuses' => $this->strategy->get_payment_statuses(), 'payment_currencies' => $this->strategy->get_currencies(), 'payment_methods' => $this->strategy->get_payment_methods(), 'post' => $post));
    }
}
