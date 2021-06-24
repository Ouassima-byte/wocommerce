<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Invoice;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF;
/**
 * Base email template for document emails.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Email
 */
class BaseEmail extends \WC_Email implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email\DocumentEmail
{
    /**
     * @var string
     */
    protected $download_url;
    /**
     * @var string
     */
    protected $document_name;
    public function __construct()
    {
        parent::__construct();
        $this->template_base = \trailingslashit(__DIR__) . 'templates/';
        $this->template_path = \trailingslashit(__DIR__) . 'templates/';
        $this->customer_email = \true;
        $this->manual = \true;
    }
    /**
     * @param string $template_base
     */
    public function set_template_base($template_base)
    {
        $this->template_base = $template_base;
    }
    /**
     * @param string $template_path
     */
    public function set_template_path($template_path)
    {
        $this->template_path = $template_path;
    }
    /**
     * Get content HTML.
     *
     * @return string
     */
    public function get_content_html()
    {
        return \wc_get_template_html($this->template_html, array('order' => $this->object, 'download_url' => $this->download_url, 'document_name' => $this->document_name, 'email_heading' => $this->get_heading(), 'sent_to_admin' => \false, 'plain_text' => \false, 'email' => $this->recipient), '', $this->template_base);
    }
    /**
     * get_content_plain function.
     *
     * @return string
     */
    public function get_content_plain()
    {
        return \wc_get_template_html($this->template_plain, array('order' => $this->object, 'download_url' => $this->download_url, 'document_name' => $this->document_name, 'email_heading' => $this->get_heading(), 'sent_to_admin' => \false, 'plain_text' => \true, 'email' => $this->recipient), '', $this->template_base);
    }
    /**
     * Initialise Settings Form Fields
     *
     * @return void
     */
    public function init_form_fields()
    {
        $this->form_fields = array('subject' => array('title' => \__('Subject', 'woocommerce'), 'type' => 'text', 'placeholder' => $this->subject, 'default' => ''), 'heading' => array('title' => \__('Email Heading', 'woocommerce'), 'type' => 'text', 'placeholder' => $this->heading, 'default' => ''), 'email_type' => array('title' => \__('Email type', 'woocommerce'), 'type' => 'select', 'description' => \__('Choose which format of email to send.', 'woocommerce'), 'default' => 'html', 'class' => 'email_type', 'options' => array('plain' => \__('Plain text', 'woocommerce'), 'html' => \__('HTML', 'woocommerce'), 'multipart' => \__('Multipart', 'woocommerce'))));
    }
    /**
     * @param \WC_Order $order    Order.
     * @param Document  $document Document.
     *
     * @throws \Exception Exception.
     */
    public function should_send_email(\WC_Order $order, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF $pdf)
    {
        /**
         * Fire hook before email send.
         *
         * @param Document $document
         * @param PDF $pdf
         */
        \do_action('fi/core/email/before/send', $document, $pdf);
        try {
            if (!\is_object($order)) {
                $order = new \WC_Order(\absint($order));
            }
            $document_name = $document->get_formatted_number();
            $download_url = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Invoice::generate_download_url($document);
            if ($order) {
                $this->object = $order;
                $this->recipient = $order->get_billing_email();
                $this->download_url = $download_url;
                $this->document_name = $document_name;
                $this->placeholders['{order_date}'] = \date(\wc_date_format(), \strtotime($order->get_date_created()->getTimestamp()));
                $this->placeholders['{order_number}'] = $order->get_order_number();
            }
            if (!$this->get_recipient()) {
                return;
            }
            $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), array());
        } catch (\Exception $e) {
            throw $e;
        }
        /**
         * Fire hook after email send.
         *
         * @param Document $document
         */
        \do_action('fi/core/email/after/send', $document);
    }
    /**
     * Get email order items.
     *
     * @param \WC_Order $order      Order.
     * @param bool      $plain_text Is plain text.
     *
     * @return string
     */
    public static function get_email_order_items($order, $plain_text = \false)
    {
        return \wc_get_email_order_items($order, array('plain_text' => $plain_text));
    }
}
