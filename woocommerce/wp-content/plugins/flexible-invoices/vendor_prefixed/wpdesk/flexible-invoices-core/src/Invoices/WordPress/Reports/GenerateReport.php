<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Reports;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Infrastructure\Request;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\DateFromToMetaQuery;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType;
use WPDeskFIVendor\WPDesk\Notice\Notice;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
/**
 * Generate report.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore
 */
class GenerateReport extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\DateFromToMetaQuery implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var SettingsStrategy
     */
    private $strategy;
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var LibraryInfo
     */
    private $library_info;
    /**
     * @param SettingsStrategy $strategy
     * @param DocumentFactory  $document_factory
     * @param Renderer         $renderer
     * @param LibraryInfo      $library_info
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo $library_info)
    {
        $this->strategy = $strategy;
        $this->document_factory = $document_factory;
        $this->renderer = $renderer;
        $this->library_info = $library_info;
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_action('wp_ajax_fiw_generate_report', array($this, 'generate_report_action'));
        if (!\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin::is_active('flexible-invoices-reports/flexible-invoices-reports.php')) {
            \add_filter('admin_init', [$this, 'advanced_report_settings_notice']);
        }
    }
    /**
     * @param string $currency
     *
     * @return array
     */
    private function get_currency_query($currency) : array
    {
        if ($currency) {
            return ['key' => '_currency', 'value' => $currency];
        }
        return [];
    }
    /**
     * Generate invoice reports from settings page.
     */
    public function generate_report_action()
    {
        $post_data = isset($_POST['reports']) ? $_POST['reports'] : [];
        if (isset($post_data['report_download']) && \wp_verify_nonce($post_data['report_download'], 'download_report') && \current_user_can('manage_options')) {
            $currency = isset($post_data['currency']) ? $post_data['currency'] : \false;
            if ($currency) {
                $currency_decimal_separator = '.';
                $inspire_invoices_currency = \get_option('inspire_invoices_currency', array());
                if (\is_array($inspire_invoices_currency)) {
                    foreach ($inspire_invoices_currency as $currency_config) {
                        if ($currency_config['currency'] == $currency) {
                            $currency_decimal_separator = $currency_config['decimal_separator'];
                            break;
                        }
                    }
                }
                $date_query = $this->get_meta_query($post_data);
                $currency_query = $this->get_currency_query($currency);
                $query_args = ['post_type' => \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME, 'orderby' => 'date', 'order' => 'ASC', 'post_status' => 'publish', 'nopaging' => \true, 'suppress_filters' => \true];
                if (!empty($date_query)) {
                    $query_args['meta_query'][] = $date_query;
                }
                if (!empty($date_query)) {
                    $query_args['meta_query'][] = $currency_query;
                }
                $query = new \WP_Query($query_args);
                $documents = [];
                $posts = $query->get_posts();
                foreach ($posts as $post) {
                    $document = $this->document_factory->get_document_creator($post->ID)->get_document();
                    if ($document->get_type() !== 'proforma') {
                        $documents[] = $this->document_factory->get_document_creator($post->ID)->get_document();
                    }
                }
                echo $this->renderer->render('report/report', array('plugin' => $this, 'library_info' => $this->library_info, 'currency_decimal_separator' => $currency_decimal_separator, 'documents' => $documents, 'settings' => $this->strategy->get_settings(), 'post_data' => $post_data));
            }
            die;
        }
    }
    /**
     * Show notice for advanced report settings tab.
     *
     * @return void
     */
    public function advanced_report_settings_notice()
    {
        $request = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Infrastructure\Request();
        $post_type = $request->param('get.post_type')->get();
        $page = $request->param('get.page')->get();
        if ($post_type === \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME && $page === 'flexible-invoices-reports-settings') {
            new \WPDeskFIVendor\WPDesk\Notice\Notice($this->renderer->render('wordpress/advanced-reports-ad', []), \WPDeskFIVendor\WPDesk\Notice\Notice::NOTICE_TYPE_SUCCESS, \true);
        }
    }
}
