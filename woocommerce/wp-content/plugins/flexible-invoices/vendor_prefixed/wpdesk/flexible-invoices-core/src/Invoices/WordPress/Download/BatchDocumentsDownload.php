<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Download;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\DocumentDecorator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\DateFromToMetaQuery;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType;
use WPDeskFIVendor\Mpdf\MpdfException;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Recursive document download.
 */
class BatchDocumentsDownload extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\DateFromToMetaQuery implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var SettingsStrategy
     */
    private $strategy;
    /**
     * @var PDF
     */
    private $pdf;
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @param SettingsStrategy $strategy
     * @param PDF              $pdf
     * @param DocumentFactory  $document_factory
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF $pdf, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory)
    {
        $this->strategy = $strategy;
        $this->pdf = $pdf;
        $this->document_factory = $document_factory;
    }
    public function hooks()
    {
        \add_action('wp_ajax_documents-batch-download', array($this, 'batch_download_action'));
    }
    /**
     * @throws MpdfException
     *
     * @internal You should not use this directly from another application
     */
    public function batch_download_action()
    {
        $post_data = isset($_POST['download']) ? $_POST['download'] : [];
        if (isset($post_data['download_invoices']) && \wp_verify_nonce($post_data['download_invoices'], 'batch_download') && \current_user_can('manage_options')) {
            $zip = new \ZipArchive();
            $filename = 'invoices.zip';
            $zip->open(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs\PDF::get_pdf_path() . $filename, \ZipArchive::CREATE);
            $invoices = $this->get_invoice_posts();
            if (!\count($invoices)) {
                $zip->addFromString('no_invoices', '');
            } else {
                foreach ($invoices as $invoice_post) {
                    $id = $invoice_post->ID;
                    $document = $this->document_factory->get_document_creator($id)->get_document();
                    $document = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\DocumentDecorator($document, $this->strategy);
                    $pdfData = $this->pdf->generate_pdf_file_content($document);
                    $zip->addFromString(\str_replace(array('/'), array('_'), $document->get_formatted_number()) . '.pdf', $pdfData);
                }
            }
            $zip->close();
            \header('Content-Type: application/zip');
            \header('Content-Disposition: attachment; filename=' . $filename);
            \readfile(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs\PDF::get_pdf_path() . $filename);
            \unlink(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs\PDF::get_pdf_path() . $filename);
            exit;
        }
    }
    /**
     * @return \WP_Post[]
     */
    private function get_invoice_posts()
    {
        $post_data = isset($_POST['download']) ? $_POST['download'] : [];
        $date_query = $this->get_meta_query($post_data);
        $query_args = ['post_type' => \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME, 'orderby' => 'date', 'order' => 'ASC', 'post_status' => 'publish', 'nopaging' => \true, 'suppress_filters' => \true];
        if (!empty($date_query)) {
            $query_args['meta_query'][] = $date_query;
        }
        $invoices_query = new \WP_Query($query_args);
        $invoices = $invoices_query->get_posts();
        return $invoices;
    }
}
