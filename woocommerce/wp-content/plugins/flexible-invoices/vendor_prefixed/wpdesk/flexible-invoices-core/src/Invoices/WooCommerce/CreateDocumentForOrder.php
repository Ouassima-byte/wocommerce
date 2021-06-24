<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use Exception;
use WC_Order;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Creators\Creator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\SaveDocument;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
/**
 * Creates documents delivered from the order and their statuses.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce
 */
class CreateDocumentForOrder implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
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
     * @var Settings
     */
    private $save_document;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var SettingsStrategy
     */
    private $strategy;
    /**
     * @var PDF
     */
    private $pdf;
    const STATUS_COMPLETED = 'completed';
    const COMPLETED_DATE = '_completed_date';
    /**
     * @param DocumentFactory  $document_factory
     * @param Settings         $settings
     * @param SaveDocument     $save_document
     * @param Renderer         $renderer
     * @param SettingsStrategy $strategy
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\SaveDocument $save_document, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF $pdf)
    {
        $this->document_factory = $document_factory;
        $this->settings = $settings;
        $this->save_document = $save_document;
        $this->renderer = $renderer;
        $this->strategy = $strategy;
        $this->pdf = $pdf;
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_action('wp_ajax_woocommere-generate-document', [$this, 'generate_document_action']);
        \add_action('woocommerce_order_status_completed', [$this, 'update_invoice_for_order_status'], 85);
        \add_action('woocommerce_order_status_processing', [$this, 'update_invoice_for_order_status'], 85);
        $this->fire_order_status_hooks();
    }
    /**
     * Fire hooks for creating documents for selected order statuses.
     */
    private function fire_order_status_hooks()
    {
        foreach ($this->document_factory->get_creators() as $creator) {
            $statuses = $creator->get_auto_create_statuses();
            if (empty($statuses)) {
                continue;
            }
            foreach ($statuses as $status) {
                \add_action('woocommerce_order_status_' . $status, [$this, 'generate_for_order_status'], 10, 2);
            }
        }
    }
    /**
     * @param WC_Order $order
     *
     * @throws Exception
     *
     * @internal You should not use this directly from another application
     */
    public function generate_for_pending_order_status(\WC_Order $order)
    {
        $this->should_auto_create_document($order);
    }
    /**
     * @param int       $id
     * @param \WC_Order $order
     *
     * @internal You should not use this directly from another application
     */
    public function generate_for_order_status($id, \WC_Order $order)
    {
        $this->should_auto_create_document($order);
    }
    /**
     * @param WC_Order $order
     *
     * @return bool
     * @throws Exception
     */
    private function should_auto_create_document(\WC_Order $order)
    {
        $order_status = $order->get_status();
        $creators = $this->document_factory->get_creators();
        if (!\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::is_pro()) {
            return \false;
        }
        foreach ($creators as $slug => $creator) {
            $auto_create_status = $creator->get_auto_create_statuses();
            $creator->set_order_id($order->get_id());
            if (\in_array($order_status, $auto_create_status) && $creator->is_allowed_for_auto_create()) {
                if ($order_status === self::STATUS_COMPLETED) {
                    $_completed_date = $order->get_meta(self::COMPLETED_DATE, \true);
                    if ($_completed_date == '') {
                        $order->update_meta_data(self::COMPLETED_DATE, \current_time('mysql'));
                        $order->save();
                    }
                }
                if ($this->is_invoice_ask($order) && $this->is_zero_invoice_ask($order)) {
                    $this->maybe_auto_generate_document_and_send_email($order, $creator);
                }
            }
        }
        return \true;
    }
    /**
     * @param WC_Order $order Order.
     * @param string   $document_type
     *
     * @return int
     * @throws Exception Document exists.
     * @internal You should not use this directly from another application
     */
    public function generate_document_for_order(\WC_Order $order, $document_type)
    {
        $wpml_user_lang = \get_post_meta($order->get_id(), 'wpml_user_lang', \true);
        $is_generated = (int) $order->get_meta('_' . $document_type . '_generated', \true);
        if (!$is_generated) {
            if (\class_exists('WPDeskFIVendor\\Translator') && !empty($wpml_user_lang)) {
                \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::set_translate_lang($wpml_user_lang);
            }
            $this->document_factory->set_document_type($document_type);
            $creator = $this->document_factory->get_document_creator($order->get_id(), \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory::ORDER_SOURCE);
            $document = $creator->get_document();
            $creator->set_order_id($order->get_id());
            if ($creator->is_allowed_for_create()) {
                try {
                    $document_id = $this->save_document->save($creator, \true);
                    if ($document_id) {
                        $order->update_meta_data('_' . $document_type . '_generated', $document_id);
                        $this->set_paid_for_order_status($document_id, $document, $order);
                        $order->save_meta_data();
                    }
                } catch (\Exception $e) {
                    throw new \Exception(\__('Document cannot be created', 'flexible-invoices'));
                }
            } else {
                throw new \Exception(\__('Document cannot be created', 'flexible-invoices'));
            }
            return $document_id;
        }
        return $is_generated;
    }
    /**
     * @param int      $document_id
     * @param Document $document
     * @param WC_Order $order
     */
    private function set_paid_for_order_status($document_id, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, \WC_Order $order)
    {
        if ($order->get_status() === 'processing' || $order->get_status() === 'completed') {
            $payment_method = $order->get_payment_method();
            if ($document->get_type() === \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice::DOCUMENT_TYPE) {
                if ('cod' === $payment_method && $order->get_status() !== 'completed') {
                    return \false;
                }
                \update_post_meta($document_id, '_total_paid', $document->get_total_gross());
                \update_post_meta($document_id, '_payment_status', 'paid');
            }
        }
        return \true;
    }
    /**
     * Fire ajax action for create document.
     *
     * @internal You should not use this directly from another application
     */
    public function generate_document_action()
    {
        $order_id = \intval($_GET['order_id']);
        if (!$order_id) {
            return \false;
        }
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        if (empty($type)) {
            return \false;
        }
        $issue_type = isset($_GET['issue_type']) ? $_GET['issue_type'] : '';
        $order = \wc_get_order($order_id);
        try {
            $document_id = $this->generate_document_for_order($order, $type);
            $creator = $this->document_factory->get_document_creator($document_id);
            $document = $creator->get_document();
            $html = $this->renderer->render('woocommerce/document-issued', ['order_has_items' => \true, 'document_number' => $document->get_formatted_number(), 'document_id' => $document->get_id(), 'order_id' => $order->get_id(), 'button_label' => $creator->get_button_label(), 'type' => $creator->get_type()]);
            if ($issue_type === 'action') {
                $order_actions = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\OrderActions();
                $html = $this->renderer->render('woocommerce/document-issued-from-action', ['actions' => $order_actions->order_status_filter([], $order)]);
            }
            \wp_send_json_success(['code' => 100, 'invoice_number' => $document->get_formatted_number(), 'result' => 'OK', 'html' => $html]);
        } catch (\Exception $e) {
            \wp_send_json_error(['code' => 101, 'invoice_number' => '', 'result' => $e->getMessage(), 'html' => '']);
        }
    }
    /**
     * @param WC_Order $order
     *
     * @return bool
     */
    private function is_invoice_ask($order)
    {
        return $this->settings->get('woocommerce_add_invoice_ask_field') !== 'yes' || $this->settings->get('woocommerce_add_invoice_ask_field') == 'yes' && $order->get_meta('_billing_invoice_ask', \true) == '1';
    }
    /**
     * @param WC_Order $order
     *
     * @return bool
     */
    private function is_zero_invoice_ask($order)
    {
        return $this->settings->get('woocommerce_zero_invoice') !== 'yes' || $this->settings->get('woocommerce_zero_invoice') === 'yes' && \intval($order->get_total()) !== 0;
    }
    /**
     * @param WC_Order $order
     * @param Creator  $creator
     *
     * @return bool
     *
     * @throws Exception
     */
    private function maybe_auto_generate_document_and_send_email(\WC_Order $order, $creator)
    {
        $is_generated = (int) $order->get_meta($creator->get_type() . '_generated', \true);
        if ($is_generated) {
            return \false;
        }
        $document_id = $this->generate_document_for_order($order, $creator->get_type());
        if ($this->should_send_email_to_customer()) {
            $document = $this->document_factory->get_document_creator($document_id)->get_document();
            $mailer = \WC()->mailer();
            $emails = $mailer->get_emails();
            $client = $document->get_customer();
            $email_class = 'fi_' . $creator->get_type();
            if (!empty($client->get_email()) && !empty($emails[$email_class])) {
                $emails[$email_class]->should_send_email($order, $document, $this->pdf);
            }
        }
        return \true;
    }
    /**
     * This settings is saved from FIS. Default is always true.
     *
     * @return bool
     */
    private function should_send_email_to_customer() : bool
    {
        return $this->settings->get('enable_sending_to_customer', 'yes') === 'yes';
    }
    /**
     * @param int $id
     *
     * @return bool
     * @internal You should not use this directly from another application
     */
    public function update_invoice_for_order_status($id)
    {
        if (!\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::is_pro()) {
            return \false;
        }
        if ('yes' === $this->settings->get('invoice_auto_paid_status')) {
            $order = \wc_get_order($id);
            $invoice_id = $order->get_meta(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice::META_GENERATED, \true);
            $document = $this->document_factory->get_document_creator($invoice_id)->get_document();
            if ($invoice_id && $document->get_id() && $document->get_type() === \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice::DOCUMENT_TYPE) {
                \update_post_meta($invoice_id, '_total_paid', $document->get_total_gross());
                \update_post_meta($invoice_id, '_payment_status', 'paid');
            }
        }
        return \true;
    }
}
