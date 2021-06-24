<?php

/**
 * Invoices. Invoice post columns.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore
 */
namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\DocumentDecorator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
/**
 * Add custom columns in documents listing.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class PostTypeColumns implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var SettingsStrategy
     */
    private $strategy;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @param SettingsStrategy $strategy
     * @param DocumentFactory  $document_factory
     * @param Renderer         $renderer
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer)
    {
        $this->strategy = $strategy;
        $this->document_factory = $document_factory;
        $this->renderer = $renderer;
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        $post_type = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME;
        \add_filter('manage_edit-' . $post_type . '_columns', [$this, 'add_custom_columns_filter']);
        \add_action('manage_' . $post_type . '_posts_custom_column', [$this, 'custom_columns_body_action'], 10, 2);
    }
    /**
     * @param array $columns
     *
     * @return array
     *
     * @internal You should not use this directly from another application
     */
    public function add_custom_columns_filter($columns)
    {
        unset($columns['date']);
        unset($columns['title']);
        $columns['invoice_title'] = \__('Invoice', 'flexible-invoices');
        $columns['client'] = \__('Customer', 'flexible-invoices');
        $columns['netto'] = \__('Net price', 'flexible-invoices');
        $columns['gross'] = \__('Gross price', 'flexible-invoices');
        $columns['issue'] = \__('Issue date', 'flexible-invoices');
        $columns['pay'] = \__('Due date', 'flexible-invoices');
        $columns['sale'] = \__('Date of sale', 'flexible-invoices');
        $columns['order'] = \__('Order', 'flexible-invoices');
        $columns['status'] = \__('Payment status', 'flexible-invoices');
        $columns['currency'] = \__('Currency', 'flexible-invoices');
        $columns['paymethod'] = \__('Payment method', 'flexible-invoices');
        $columns['actions'] = \__('Actions', 'flexible-invoices');
        /**
         * Adds custom columns header to the documents list.
         *
         * @param array $columns Columns header.
         *
         * @since 3.0.0
         */
        $columns = \apply_filters('fi/core/lists/columns/header', $columns);
        return $columns;
    }
    /**
     * @param Document $document
     *
     * @return float|string
     */
    private function check_total_amount(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document)
    {
        switch ($document->get_type()) {
            case 'correction':
                return $this->total_refund($document);
            default:
                return $document->get_total_gross();
        }
    }
    /**
     * @param Document $document
     *
     * @return float|string
     */
    private function total_refund(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document)
    {
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            $order = \wc_get_order($document->get_order_id());
            if (!$order) {
                return $document->get_total_gross();
            }
            $refunds = $order->get_refunds();
            if (!$refunds || empty($refunds)) {
                return $document->get_total_gross();
            }
            $total = 0;
            foreach ($refunds as $refund) {
                if ($refund instanceof \WC_Order_Refund) {
                    $total = $refund->get_total();
                }
            }
            if ($total !== $document->get_total_gross()) {
                return '<span class="amount-error" data-tip="Amount is not equal with order.">' . $document->get_total_gross() . '</span>';
            }
        }
        return $document->get_total_gross();
    }
    /**
     * @param string $column_name Column name,
     * @param int    $post_id     Post ID.
     *
     * @internal You should not use this directly from another application
     */
    public function custom_columns_body_action($column_name, $post_id)
    {
        global $post;
        $creator = $this->document_factory->get_document_creator($post->ID);
        $document = $creator->get_document();
        $document = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\DocumentDecorator($document, $this->strategy);
        switch ($column_name) {
            case 'invoice_title':
                $duplicates = $this->find_duplicates($post->post_title);
                $class = '';
                $title_duplicated = '';
                if ($duplicates > 1) {
                    $class = 'is_duplicated';
                    $title_duplicated = \__('The name of invoice is duplicated!', 'flexible-invoices');
                }
                if (empty($post->post_title)) {
                    $post->post_title = $document->get_formatted_number();
                }
                if (!$creator->is_allowed_for_edit()) {
                    echo \sprintf('<span class="%s"><strong>%s</strong></span>', $class, $post->post_title);
                } else {
                    echo \sprintf('<strong><a class="%s" title="%s" href="%s">%s</a></strong>', $class, $title_duplicated, \get_edit_post_link($post_id), $post->post_title);
                }
                break;
            case 'client':
                echo $document->get_customer()->get_name();
                break;
            case 'netto':
                echo $document->get_total_net();
                break;
            case 'gross':
                echo $document->get_total_gross();
                break;
            case 'issue':
                echo $document->get_date_of_issue();
                break;
            case 'pay':
                echo $document->get_date_of_pay();
                break;
            case 'order':
                $order_id = (int) $document->get_order_id();
                if ($order_id) {
                    $order_number = $order_id;
                    if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
                        $order = \wc_get_order($order_id);
                        if ($order) {
                            $order_number = $order->get_order_number();
                        }
                    }
                    echo '<a href="' . \admin_url('post.php?post=' . $order_id . '&action=edit') . '">' . $order_number . '</a>';
                }
                break;
            case 'status':
                echo $document->get_payment_status_name();
                break;
            case 'sale':
                echo $document->get_date_of_sale();
                break;
            case 'currency':
                echo $document->get_currency();
                break;
            case 'paymethod':
                echo $document->get_payment_method_name();
                break;
            case 'actions':
                echo '<a target="_blank" href="' . \site_url() . '/wp-admin/admin-ajax.php?action=invoice-get-pdf-invoice&amp;id=' . $document->get_id() . '&amp;hash=' . \md5(NONCE_SALT . $document->get_id()) . '" class="button tips dashicons view-invoice" title="' . \__('View Invoice', 'flexible-invoices') . '">' . \__('View Invoice', 'flexible-invoices') . '</a>';
                echo '<a target="_blank" href="' . \site_url() . '/wp-admin/admin-ajax.php?action=invoice-get-pdf-invoice&amp;id=' . $document->get_id() . '&amp;hash=' . \md5(NONCE_SALT . $document->get_id()) . '&save_file=1" class="button tips dashicons get-invoice" title="' . \__('Download Invoice', 'flexible-invoices') . '">' . \__('Download Invoice', 'flexible-invoices') . '</a>';
                break;
            default:
                echo \get_post_meta($post_id, '_invoice_' . $column_name, \true);
                break;
        }
        /**
         * Adds body for custom columns to the documents list.
         *
         * @param array    $column_name Column name.
         * @param Document $document    Document.
         *
         * @since 3.0.0
         */
        \do_action('fi/core/lists/columns/body', $column_name, $document);
    }
    /**
     * Find duplicates.
     *
     * @param string $post_title Post title.
     *
     * @return int
     */
    private function find_duplicates($post_title)
    {
        global $wpdb;
        $duplicates = $wpdb->get_var($wpdb->prepare("SELECT count(ID) FROM {$wpdb->posts} WHERE `post_title` = %s AND `post_type` = %s AND `post_status` = 'publish'", $post_title, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME));
        return (int) $duplicates;
    }
}
