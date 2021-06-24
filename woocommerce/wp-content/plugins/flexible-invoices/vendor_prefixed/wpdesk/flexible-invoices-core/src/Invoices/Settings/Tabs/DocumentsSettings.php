<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields\DocumentsFieldsInterface;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
/**
 * Document Settings Tab Page.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs
 */
final class DocumentsSettings extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\FieldSettingsTab
{
    private $form_fields = [];
    /**
     * @var SettingsStrategy
     */
    private $strategy;
    /**
     * @param SettingsStrategy $strategy
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy)
    {
        $this->strategy = $strategy;
        $this->set_sub_tab_forms();
    }
    /**
     * Set document fields form.
     */
    private function set_sub_tab_forms()
    {
        /**
         * @var DocumentsFields\DocumentsFieldsInterface[] $settings
         */
        $documents_settings = ['invoice' => new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields\InvoicesSettingsFields($this->strategy)];
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            $documents_settings['proforma'] = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields\ProformaSettingsFields($this->strategy);
            $documents_settings['correction'] = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields\CorrectionsSettingsFields();
        }
        /**
         * Definitions of settings for Document tab.
         *
         * @param DocumentsFieldsInterface[] $documents_settings Document Settings Tabs.
         *
         * @since (1.2.0)
         *
         */
        $settings = (array) \apply_filters('fi/core/settings/documents', $documents_settings);
        foreach ($settings as $setting) {
            if ($setting instanceof \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields\DocumentsFieldsInterface) {
                $this->form_fields[$setting::get_tab_slug()] = $setting->get_fields();
            }
        }
    }
    /**
     * @return array|\WPDesk\Forms\Field[]
     */
    public function get_fields()
    {
        $fields = [];
        foreach ($this->form_fields as $form) {
            foreach ($form as $field) {
                $fields[] = $field;
            }
        }
        return $fields;
    }
    /**
     * @return string
     */
    public static function get_tab_slug()
    {
        return 'documents';
    }
    /**
     * @return string
     */
    public function get_tab_name()
    {
        return \__('Documents', 'flexible-invoices');
    }
}
