<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields;

use WPDeskFIVendor\WPDesk\Forms\Field\TextAreaField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubEndField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubStartField;
use WPDeskFIVendor\WPDesk\Forms\Field\Header;
use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;
use WPDeskFIVendor\WPDesk\Forms\Field\SelectField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\WPMLFieldDecorator;
/**
 * Correction Document Settings Sub Page.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields
 */
final class CorrectionsSettingsFields implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields\DocumentsFieldsInterface
{
    /**
     * @return string
     */
    private function get_doc_link()
    {
        return \sprintf('<a href="%s" target="_blank">%s</a>', \esc_url(\get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/docs/faktury-korygujace-woocommerce/?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=settings-docs-link' : 'https://www.wpdesk.net/docs/flexible-invoices-woocommerce-corrections-docs/?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=settings-docs-link', array('https')), \esc_html__('Check how to issue corrective invoices.', 'flexible-invoices'));
    }
    /**
     * @return string
     */
    private function get_pro_class()
    {
        if (!\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::is_pro()) {
            return 'pro-version';
        }
        return '';
    }
    private function get_beacon_translations() : string
    {
        return \__('Correction Settings', 'flexible-invoices');
    }
    /**
     * @inheritDoc
     */
    public function get_fields()
    {
        $invoice_beacon = $this->get_beacon_translations();
        return [(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubStartField())->set_label(\__('Correction', 'flexible-invoices'))->set_name('correction')->add_class($this->get_pro_class()), (new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_label(\__('Correction Settings', 'flexible-invoices'))->set_description($this->get_doc_link()), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('enable_corrections')->set_label(\__('Automatic Corrections', 'flexible-invoices'))->set_sublabel(\__('Enable automatic corrections generation for order refunds.', 'flexible-invoices'))->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $invoice_beacon), (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('correction_number_reset_type')->set_label(\__('Number Reset', 'flexible-invoices'))->set_description(\__('Select when to reset the correction number to 1.', 'flexible-invoices'))->set_options(['year' => \__('Yearly', 'flexible-invoices'), 'month' => \__('Monthly', 'flexible-invoices'), 'none' => \__('None', 'flexible-invoices')])->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $invoice_beacon), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('correction_start_number')->set_label(\__('Next Number', 'flexible-invoices'))->set_description(\__('Enter the next correction number. The default value is 1 and changes every time a correction is issued. Existing corrections won\'t be changed.', 'flexible-invoices'))->add_class('regular-text edit_disabled_field hs-beacon-search')->set_attribute('type', 'number')->set_attribute('disabled', 'disabled')->set_attribute('data-beacon_search', $invoice_beacon), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\WPMLFieldDecorator((new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('correction_number_prefix')->set_label(\__('Prefix', 'flexible-invoices'))->set_default_value(1)->set_default_value(\__('Corrected invoice', 'flexible-invoices'))->add_class('regular-text hs-beacon-search')->set_description(\__('For prefixes use the following short tags: <code>{DD}</code> for day, <code>{MM}</code> for month, <code>{YYYY}</code> for year.', 'flexible-invoices'))->set_attribute('data-beacon_search', $invoice_beacon)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\WPMLFieldDecorator((new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('correction_number_suffix')->set_label(\__('Suffix', 'flexible-invoices'))->set_default_value(\__('/{MM}/{YYYY}', 'flexible-invoices'))->add_class('regular-text hs-beacon-search')->set_description(\__('For suffixes use the following short tags: <code>{DD}</code> for day, <code>{MM}</code> for month, <code>{YYYY}</code> for year.', 'flexible-invoices'))->set_attribute('data-beacon_search', $invoice_beacon)))->get_field(), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('correction_default_due_time')->set_label(\__('Default Due Time', 'flexible-invoices'))->set_default_value(0)->set_attribute('type', 'number')->add_class('regular-text hs-beacon-search')->set_attribute('data-beacon_search', $invoice_beacon), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\WPMLFieldDecorator((new \WPDeskFIVendor\WPDesk\Forms\Field\TextAreaField())->set_name('correction_notes')->set_label(\__('Reason', 'flexible-invoices'))->set_default_value(\__('Refund', 'flexible-invoices'))->add_class('large-text hs-beacon-search')->set_attribute('data-beacon_search', $invoice_beacon)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubEndField())->set_label('')];
    }
    /**
     * @inheritDoc
     */
    public static function get_tab_slug()
    {
        return 'corrections';
    }
    /**
     * @inheritDoc
     */
    public function get_tab_name()
    {
        return \__('Corrections', 'flexible-invoices');
    }
}
