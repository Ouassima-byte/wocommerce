<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs\Currency;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\TableGroupedFields;
use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;
use WPDeskFIVendor\WPDesk\Forms\Field\SelectField;
final class CurrencySettings extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\FieldSettingsTab
{
    const TAX_RATES_FIELD = 'currency';
    const CURRENCY_FIELD = 'currency';
    const CURRENCY_POSITION_FIELD = 'currency_position';
    const THOUSAND_SEPARATOR_FIELD = 'thousand_separator';
    const FIELD_DECIMAL_SEP_FIELD = 'decimal_separator';
    /**
     * @return array|\WPDesk\Forms\Field[]
     */
    protected function get_fields()
    {
        $invoice_beacon = 'Currencies for invoicing in WordPress';
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            $invoice_beacon = 'Currencies for invoicing in WooCommerce';
        }
        return [(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\TableGroupedFields())->set_name(self::TAX_RATES_FIELD)->set_items([(new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name(self::CURRENCY_FIELD)->add_class('currency hs-beacon-search ')->set_options(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs\Currency::get_currencies_options())->set_attribute('data-beacon_search', $invoice_beacon), (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name(self::CURRENCY_POSITION_FIELD)->add_class('currency-position hs-beacon-search ')->set_options(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs\Currency::get_currency_position_options())->set_attribute('data-beacon_search', $invoice_beacon), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::THOUSAND_SEPARATOR_FIELD)->add_class('thousand-separator hs-beacon-search')->set_attribute('minlength', 0)->set_attribute('maxlength', 1)->set_placeholder(',')->set_attribute('data-beacon_search', $invoice_beacon), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::FIELD_DECIMAL_SEP_FIELD)->add_class('decimal-separator hs-beacon-search')->set_attribute('minlength', 0)->set_attribute('maxlength', 1)->set_placeholder('.')->set_attribute('data-beacon_search', $invoice_beacon)])];
    }
    /**
     * @return string
     */
    public static function get_tab_slug()
    {
        return 'currency';
    }
    /**
     * @return string
     */
    public function get_tab_name()
    {
        return \__('Currencies', 'flexible-invoices');
    }
}
