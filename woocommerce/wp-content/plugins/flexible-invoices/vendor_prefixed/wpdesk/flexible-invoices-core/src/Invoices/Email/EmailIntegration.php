<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Invoice;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Infrastructure\Request;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * @package WPDesk\Library\FlexibleInvoicesCore\Email
 */
class EmailIntegration implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
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
     * @var PDF
     */
    private $pdf;
    /**
     * @param DocumentFactory $document_factory
     * @param Settings        $settings
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF $pdf)
    {
        $this->document_factory = $document_factory;
        $this->settings = $settings;
        $this->pdf = $pdf;
    }
    /**
     * Fire hooks.
     */
    public function hooks()
    {
        \add_action('wp_ajax_invoice-send-by-email', array($this, 'send_invoice_by_email_action'));
        \add_action('inspire_invoices_after_display_options_metabox_actions', array($this, 'add_send_mail_option_action'));
    }
    /**
     * @param Document $document
     *
     * @internal You should not use this directly from another application
     */
    public function add_send_mail_option_action(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document)
    {
        $creator = $this->document_factory->get_document_creator($document->get_id());
        $order = \wc_get_order($document->get_order_id());
        if ($order && $creator->is_allowed_to_send()) {
            echo '<button data-id="' . $document->get_id() . '" data-type="fi_' . $document->get_type() . '" data-hash="' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Invoice::document_hash($document) . '" class="button button-primary button-large send_invoice">' . \__('Send by e-mail', 'flexible-invoices') . '</button>';
        }
    }
    /**
     * @return void
     *
     * @internal You should not use this directly from another application
     */
    public function send_invoice_by_email_action()
    {
        $request = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Infrastructure\Request();
        $id = (int) $request->param('post.id')->get();
        $email_class = $request->param('post.email_class')->get();
        if ($id) {
            $document = $this->document_factory->get_document_creator($id)->get_document();
            $order_id = $document->get_order_id();
            if ($order_id) {
                $order = \wc_get_order($order_id);
                $client = $document->get_customer();
                $send = $this->send_email($order, $document, $email_class);
                if ($send) {
                    \wp_send_json_success(['code' => 100, 'invoice_number' => $document->get_formatted_number(), 'result' => 'OK', 'email' => $client->get_email()]);
                }
            } else {
                \wp_send_json_error(['code' => 101, 'invoice_number' => $document->get_formatted_number(), 'result' => 'Fail']);
            }
        }
        \wp_send_json_error(['code' => 103, 'invoice_number' => $document->get_formatted_number(), 'result' => 'OK']);
    }
    /**
     * @param \WC_Order $order
     * @param Document  $document
     * @param string    $email_class
     *
     * @return bool
     */
    public function send_email(\WC_Order $order, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, $email_class)
    {
        $mailer = \WC()->mailer();
        $emails = $mailer->get_emails();
        $client = $document->get_customer();
        if (!empty($client->get_email()) && !empty($emails[$email_class])) {
            $emails[$email_class]->should_send_email($order, $document, $this->pdf);
            return \true;
        } else {
            return \false;
        }
    }
}
