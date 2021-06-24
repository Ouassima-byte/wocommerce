<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Reports;

use WPDeskFIVendor\WPDesk\Forms\Resolver\DefaultFormFieldResolver;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FixedSubmitField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\GroupedFields;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType;
use WPDeskFIVendor\WPDesk\Forms\Field\Header;
use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;
use WPDeskFIVendor\WPDesk\Forms\Field\NoOnceField;
use WPDeskFIVendor\WPDesk\Forms\Field\SelectField;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
use WPDeskFIVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use WPDeskFIVendor\WPDesk\View\Resolver\ChainResolver;
use WPDeskFIVendor\WPDesk\View\Resolver\DirResolver;
/**
 * Register document creators.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class ReportsMenuPage implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var string;
     */
    const MENU_SLUG = 'flexible-invoices-reports-settings';
    const NONCE_ACTION = 'download_report';
    const NONCE_NAME = 'report_download';
    const REPORTS_PLUGIN_SLUG = 'flexible-invoices-reports/flexible-invoices-reports.php';
    /**
     * @var string
     */
    private $template_dir;
    /**
     * @param string $template_dir
     */
    public function __construct($template_dir)
    {
        $this->template_dir = $template_dir;
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        if (!\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin::is_active(self::REPORTS_PLUGIN_SLUG)) {
            \add_action('admin_menu', function () {
                \add_submenu_page(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_MENU_URL, $this->get_tab_name(), $this->get_tab_name(), 'manage_options', self::MENU_SLUG, [$this, 'render_page_action'], 10);
            });
            if (isset($_GET['page']) && isset($_GET['tab']) && $_GET['page'] === self::MENU_SLUG) {
                \add_filter('admin_body_class', function ($classes) {
                    return $classes . ' settings-' . $_GET['tab'];
                });
            }
        }
    }
    /**
     * @return \WPDesk\View\Renderer\Renderer
     */
    private function get_renderer()
    {
        $resolver = new \WPDeskFIVendor\WPDesk\View\Resolver\ChainResolver();
        $resolver->appendResolver(new \WPDeskFIVendor\WPDesk\View\Resolver\DirResolver($this->template_dir . 'settings'));
        $resolver->appendResolver(new \WPDeskFIVendor\WPDesk\Forms\Resolver\DefaultFormFieldResolver());
        return new \WPDeskFIVendor\WPDesk\View\Renderer\SimplePhpRenderer($resolver);
    }
    /**
     * @return void
     */
    public function render_page_action()
    {
        $renderer = $this->get_renderer();
        $content = $renderer->render('form-start', [
            'form' => $this,
            'method' => 'POST',
            // backward compat
            'action' => '',
        ]);
        $content .= $this->render_fields($renderer);
        $content .= $renderer->render('form-end');
        echo $content;
    }
    /**
     * @param Renderer $renderer
     *
     * @return string
     */
    public function render_fields(\WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer)
    {
        $content = '';
        $fields_data = [];
        //$this->get_data();
        foreach ($this->get_fields() as $field) {
            $content .= $renderer->render($field->should_override_form_template() ? $field->get_template_name() : 'form-field', ['field' => $field, 'renderer' => $renderer, 'name_prefix' => $this->get_form_id(), 'value' => isset($fields_data[$field->get_name()]) ? $fields_data[$field->get_name()] : $field->get_default_value(), 'template_name' => $field->get_template_name()]);
        }
        return $content;
    }
    /**
     * @return array
     */
    private function get_currencies()
    {
        $currencies_options = [];
        $currencies = \get_option('inspire_invoices_currency', array());
        foreach ($currencies as $currency) {
            $currencies_options[$currency['currency']] = $currency['currency'];
        }
        return $currencies_options;
    }
    /**
     * @return array|\WPDesk\Forms\Field[]
     */
    protected function get_fields()
    {
        return [(new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_label(\__('Reports', 'flexible-invoices')), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\GroupedFields())->set_name('grouped_field')->set_grouped_fields([(new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('start_date')->set_label(\__('From:', 'flexible-invoices'))->add_class('medium-text datepicker hs-beacon-search')->set_default_value(\date('Y-m-d', \strtotime('NOW - 1 months')))->set_attribute('data-beacon_search', 'Reports'), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('end_date')->set_label(\__('To:', 'flexible-invoices'))->add_class('medium-text datepicker hs-beacon-search')->set_default_value(\date('Y-m-d', \strtotime('NOW')))->set_attribute('data-beacon_search', 'Reports')]), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\GroupedFields())->set_name('grouped_field')->set_grouped_fields([(new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('currency')->set_label(\__('Currency:', 'flexible-invoices'))->set_options($this->get_currencies())]), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FixedSubmitField())->set_name('download_report')->set_label(\__('Generate', 'flexible-invoices'))->add_class('button-primary'), (new \WPDeskFIVendor\WPDesk\Forms\Field\NoOnceField(self::NONCE_ACTION))->set_name(self::NONCE_NAME)];
    }
    /**
     * @return string
     */
    public function get_method()
    {
        return 'POST';
    }
    /**
     * @return string
     */
    public function get_action()
    {
        return \admin_url('admin-ajax.php?action=fiw_generate_report');
    }
    /**
     * @return string
     */
    public function get_form_id()
    {
        return 'reports';
    }
    /**
     * @return string
     */
    public static function get_tab_slug()
    {
        return 'reports';
    }
    /**
     * @return string
     */
    public function get_tab_name()
    {
        return \__('Reports', 'flexible-invoices');
    }
}
