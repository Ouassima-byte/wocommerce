<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use Exception;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\DocumentDecorator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\MetaPostContainer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\Mpdf\Mpdf;
use WPDeskFIVendor\Mpdf\MpdfException;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
/**
 * Generate & download PDF.
 */
class PDF implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var array
     */
    private $config = [];
    /**
     * @var LibraryInfo
     */
    private $library_info;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @var SettingsStrategy
     */
    private $strategy;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @param LibraryInfo      $library_info
     * @param Renderer         $renderer
     * @param DocumentFactory  $document_factory
     * @param SettingsStrategy $strategy
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo $library_info, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy)
    {
        $this->library_info = $library_info;
        $this->renderer = $renderer;
        $this->document_factory = $document_factory;
        $this->strategy = $strategy;
        $this->settings = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings();
        $this->set_default_config();
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_action('wp_ajax_fiw_get_document', array($this, 'get_document_action'));
        \add_action('wp_ajax_nopriv_fiw_get_document', array($this, 'get_document_action'));
        \add_action('wp_ajax_invoice-get-pdf-invoice', array($this, 'get_invoice_pdf_action'));
        \add_action('wp_ajax_nopriv_invoice-get-pdf-invoice', array($this, 'get_invoice_pdf_action'));
    }
    /**
     * @return Mpdf
     * @throws MpdfException
     */
    public function get()
    {
        return new \WPDeskFIVendor\Mpdf\Mpdf($this->get_config());
    }
    /**
     * @param array $config
     */
    public function set_config(array $config)
    {
        $this->config = $config;
    }
    /**
     * @return array
     */
    private function get_config()
    {
        return $this->config;
    }
    /**
     * @return string
     */
    private function temp_dir()
    {
        $upload_dir = \wp_upload_dir();
        $temp_dir = \trailingslashit($upload_dir['basedir']) . 'mpdf/tmp/';
        \wp_mkdir_p($temp_dir);
        return $temp_dir;
    }
    /**
     * @return array
     */
    public function get_font_dir()
    {
        /**
         * Change default font dir.
         *
         * @param array $font_dir Font dirs.
         *
         * @since 3.0.0
         *
         */
        return (array) \apply_filters('fi/core/pdf/fonts/dir', [\trailingslashit($this->library_info->get_assets_dir() . 'fonts')]);
    }
    /**
     * @return array
     */
    private function fonts_data()
    {
        $fonts = ['dejavuserif' => array('R' => 'DejaVuSerif.ttf', 'I' => 'DejaVuSerif.ttf', 'B' => 'DejaVuSerif-Bold.ttf', 'BI' => 'DejaVuSerif-Bold.ttf'), 'dejavusanscondensed' => array('R' => 'DejaVuSansCondensed.ttf', 'I' => 'DejaVuSansCondensed.ttf', 'B' => 'DejaVuSansCondensed-Bold.ttf', 'BI' => 'DejaVuSansCondensed-Bold.ttf')];
        /**
         * Change default fonts data.
         *
         * @param array $fonts Fonts data.
         *
         * @since 3.0.0
         *
         */
        return (array) \apply_filters('fi/core/pdf/fonts/data', $fonts);
    }
    /**
     * Set default config
     */
    private function set_default_config()
    {
        $config = ['mode' => '+aCJK', 'format' => 'A4', 'autoLangToFont' => \true, 'autoScriptToLang' => \true, 'tempDir' => $this->temp_dir(), 'fontDir' => $this->get_font_dir(), 'fontdata' => $this->fonts_data(), 'default_font' => 'dejavusanscondensed'];
        /**
         * Change PDF config.
         *
         * @param array $fonts Fonts data.
         *
         * @since 3.0.0
         *
         */
        $this->set_config((array) \apply_filters('fi/core/pdf/config', $config));
    }
    /**
     * @param Document $document
     *
     * @return string
     * @throws MpdfException
     */
    public function generate_pdf_file_content(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document)
    {
        $mpdf = $this->get();
        $mpdf->img_dpi = 200;
        if (!\is_a($document, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\DocumentDecorator::class)) {
            $document = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\DocumentDecorator($document, $this->strategy);
        }
        $mpdf->WriteHTML($this->get_document_template($document));
        return $mpdf->Output(\str_replace(array('/'), array('_'), $document->get_formatted_number()) . '.pdf', 'S');
    }
    /**
     * Debug HTML before render.
     *
     * Define FLEXIBLE_INVOICES_DEBUG in wp-config.php if you want display HTML not PDF in browser.
     *
     * @param Document $document
     */
    public function debug_before_render_pdf(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document)
    {
        echo $this->get_document_template($document);
        die;
    }
    /**
     * @param int $document_id
     *
     * @return void
     * @throws MpdfException
     */
    public function send_to_browser($document_id)
    {
        $post = \get_post($document_id);
        if (!$post) {
            \wp_die(\__('This document doesn\'t exist or was deleted.', 'flexible-invoices'));
        }
        $document = $this->document_factory->get_document_creator($document_id)->get_document();
        $invoice = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\DocumentDecorator($document, $this->strategy);
        $name = \str_replace(array('/', ' '), array('_', '_'), $invoice->get_formatted_number()) . '.pdf';
        if (\defined('FLEXIBLE_INVOICES_DEBUG')) {
            $this->debug_before_render_pdf($invoice);
        }
        \header('Content-type: application/pdf');
        if (isset($_GET['save_file'])) {
            \header('Content-Disposition: attachment; filename="' . $name . '"');
        } else {
            \header('Content-Disposition: inline; filename="' . $name . '"');
        }
        $pdf_data = $this->generate_pdf_file_content($invoice);
        echo $pdf_data;
        exit;
    }
    /**
     * @param null $id
     *
     * @throws MpdfException
     * @internal You should not use this directly from another application
     */
    public function get_invoice_pdf_action($id = null)
    {
        if (empty($id)) {
            $id = $_GET['id'];
        }
        if (isset($_GET['hash']) && $_GET['hash'] == \md5(NONCE_SALT . $id) || \current_user_can('manage_options') || \current_user_can('manage_woocommerce')) {
            $this->send_to_browser($id);
        }
        die;
    }
    /**
     * @throws MpdfException
     * @internal You should not use this directly from another application
     */
    public function get_document_action()
    {
        $id = (int) $_GET['id'];
        $creator = $this->document_factory->get_document_creator($id);
        $document = $creator->get_document();
        $hash = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Invoice::document_hash($document);
        if (isset($_GET['hash']) && $_GET['hash'] === $hash) {
            $this->send_to_browser($id);
        }
        die;
    }
    /**
     * @param Document $document
     *
     * @return string
     */
    private function get_document_template(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document)
    {
        $corrected_id = $document->get_corrected_id();
        $corrected_invoice = $this->document_factory->get_document_creator($corrected_id)->get_document();
        $corrected_invoice_pdf = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\DocumentDecorator($corrected_invoice, $this->strategy);
        try {
            return $this->renderer->render('documents/' . $document->get_type(), array('invoice' => $document, 'currency_helper' => new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Currency($document->get_currency()), 'meta' => new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\MetaPostContainer($document->get_id()), 'translator' => new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator(), 'library_info' => $this->library_info, 'settings' => $this->strategy->get_settings(), 'corrected_invoice' => $corrected_invoice_pdf));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
