<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration;

use Psr\Log\LoggerInterface;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Creators\AbstractDocumentCreator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Creator\DocumentCreator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\PostMetaDocumentDecorator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType;
use WPDeskFIVendor\WPDesk\Mutex\WordpressMySQLLockMutex;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Save document as custom post type.
 *
 * This class creates document as custom post type and saves post meta.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class SaveDocument implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var SettingsStrategy
     */
    private $strategy;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $plugin_version;
    /**
     * @param DocumentFactory  $document_factory
     * @param Settings         $settings
     * @param SettingsStrategy $strategy
     * @param LoggerInterface  $logger
     * @param string           $plugin_version
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy, \Psr\Log\LoggerInterface $logger, $plugin_version)
    {
        $this->document_factory = $document_factory;
        $this->settings = $settings;
        $this->strategy = $strategy;
        $this->logger = $logger;
        $this->plugin_version = $plugin_version;
    }
    /**
     * Fire hooks.
     */
    public function hooks()
    {
        \add_action('save_post', array($this, 'save_custom_fields_action'), 2, 2);
    }
    /**
     * @param int      $post_id
     * @param \WP_Post $post
     *
     * @return false|int
     */
    public function save_custom_fields_action($post_id, $post)
    {
        if (!isset($_POST['flexible_invoices_nonce'])) {
            return \false;
        }
        if (!\wp_verify_nonce($_POST['flexible_invoices_nonce'], 'flexible_invoices_nonce')) {
            return \false;
        }
        if (\defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return \false;
        }
        if ($post->post_status === 'auto-draft') {
            return \false;
        }
        $creator = $this->document_factory->get_document_creator($post_id, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory::POST_SOURCE);
        try {
            $this->save($creator);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $post_id;
    }
    /**
     * @param DocumentCreator $document_creator
     * @param bool            $should_insert_post
     *
     * @return int
     */
    public function save(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Creator\DocumentCreator $document_creator, $should_insert_post = \false)
    {
        try {
            $document = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\PostMetaDocumentDecorator($document_creator->get_document(), $this->strategy);
            $mutex = new \WPDeskFIVendor\WPDesk\Mutex\WordpressMySQLLockMutex('_fiw_mutex', 30);
            if (!$mutex->acquireLock()) {
                throw new \RuntimeException("Cannot acquire lock");
            }
            try {
                $numbering = $document_creator->get_document_numbering($document);
                $formatted_number = $numbering->get_formatted_number();
                if ($should_insert_post) {
                    $document_id = $this->should_insert_post($formatted_number);
                    if ($document_id === 0) {
                        throw new \RuntimeException("Cannot insert Invoice post");
                    }
                    $document->set_id($document_id);
                } else {
                    $document_id = $document->get_id();
                }
                $meta = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\MetaPostContainer($document_id);
                if (empty($meta->get('_formatted_number'))) {
                    $numbering->increase_number();
                }
                $meta->set('_formatted_number', $formatted_number);
                $meta->set('_number', $numbering->get_number());
                unset($numbering);
            } finally {
                $mutex->releaseLock();
            }
            $customer = $document->get_customer();
            $meta->set('_date_issue', $document->get_date_of_issue());
            $meta->set('_date_sale', $document->get_date_of_sale());
            $meta->set('_date_pay', $document->get_date_of_pay());
            $meta->set('_date_paid', $document->get_date_of_paid());
            $meta->set('_products', $document->get_items());
            $meta->set('_client', $customer);
            $meta->set('_client_vat_number', $customer['nip']);
            $meta->set('_client_email', $customer['email']);
            $meta->set('_client_country', $customer['country']);
            $meta->set('_client_name', $customer['name']);
            $meta->set('_client_filter_field', $customer['name']);
            $meta->set('_owner', $document->get_seller());
            $meta->set('_total_price', \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals::calculate_total_gross($document->get_items()));
            $meta->set('_total_net', \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals::calculate_total_net($document->get_items()));
            $meta->set('_total_tax', \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals::calculate_total_vat($document->get_items()));
            $meta->set('_total_paid', $document->get_total_paid());
            $meta->set('_discount', $document->get_discount());
            $meta->set('_currency', $document->get_currency());
            $meta->set('_type', $document->get_type());
            $meta->set('_payment_status', $document->get_payment_status());
            $meta->set('_payment_method', $document->get_payment_method());
            $meta->set('_payment_method_name', $document->get_payment_method_name());
            $meta->set('_notes', $document->get_notes());
            $meta->set('wpml_user_lang', $document->get_user_lang());
            $meta->set('_add_order_id', $document->get_show_order_number());
            $meta->set('_wc_order_id', $document->get_order_id());
            $meta->set('_version', $this->plugin_version);
            $document_creator->custom_meta($document, $meta)->save();
            /**
             * Save custom post meta for the document from external plugins.
             *
             * @param Document          $document    Document type.
             * @param MetaPostContainer $meta        Meta Container.
             * @param int               $document_id Document ID.
             *
             * @since 3.0.0
             */
            \do_action('fi/core/document/save', $document, $meta, $document_id);
            \sleep(1);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $document_id;
    }
    /**
     * @param $title
     *
     * @return int
     */
    private function should_insert_post($title)
    {
        $invoice_post = ['post_title' => $title, 'post_content' => '', 'post_status' => 'publish', 'post_type' => \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME, 'post_date' => \current_time('mysql')];
        return (int) \wp_insert_post($invoice_post);
    }
}
