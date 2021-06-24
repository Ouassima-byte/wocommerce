<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\TableGroupedFields;
use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;
final class TaxRatesSettings extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\FieldSettingsTab
{
    const TAX_RATES_FIELD = 'tax';
    const TAX_NAME = 'name';
    const TAX_RATE = 'rate';
    /**
     * @return array|\WPDesk\Forms\Field[]
     */
    protected function get_fields()
    {
        $invoice_beacon = 'Tax Rates (WordPress Only)';
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            $invoice_beacon = 'Settings for tax payers';
        }
        return [(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\TableGroupedFields())->set_name(self::TAX_RATES_FIELD)->set_items([(new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::TAX_NAME)->add_class('tax-name')->set_placeholder('20%')->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $invoice_beacon), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::TAX_RATE)->add_class('tax-rate')->set_placeholder('20')->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $invoice_beacon)])];
    }
    /**
     * @return string
     */
    public static function get_tab_slug()
    {
        return 'tax-rates';
    }
    /**
     * @return string
     */
    public function get_tab_name()
    {
        return \__('Tax rates', 'flexible-invoices');
    }
    public static function is_active()
    {
        return !\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active();
    }
}
