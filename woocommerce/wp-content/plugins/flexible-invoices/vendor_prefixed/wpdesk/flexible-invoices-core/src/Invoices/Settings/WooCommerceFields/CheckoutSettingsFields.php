<?php

/**
 * Woocommerce Settings.
 *
 * @package WPDesk\FlexibleInvoicesWooCommerce
 */
namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubEndField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubStartField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\Forms\Field\Header;
use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;
/**
 * Checkout settings subpage.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields
 */
final class CheckoutSettingsFields implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields\SubTabInterface
{
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
    }
    /**
     * @return array
     */
    public function get_order_statuses()
    {
        $statuses = $this->strategy->get_order_statuses();
        unset($statuses['completed']);
        return $statuses;
    }
    /**
     * @inheritDoc
     */
    public function get_fields()
    {
        $plugin_url = \get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/woocommerce-checkout-fields/' : 'https://www.wpdesk.net/products/flexible-checkout-fields-pro/';
        $plugin_url .= '?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-checkout-fields';
        $checkout = 'Checkout form';
        return [(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubStartField())->set_label(\__('Checkout', 'flexible-invoices'))->set_name('checkout'), (new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_label(\__('Checkout', 'flexible-invoices'))->set_description(\sprintf(\__('Warning. If you use a plugin for editing <a href="%s">checkout fields</a> it may override the following settings.', 'flexible-invoices'), $plugin_url)), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('woocommerce_add_invoice_ask_field')->set_label(\__('Ask the customer if he wants an invoice', 'flexible-invoices'))->set_sublabel(\__('Enable', 'flexible-invoices'))->set_description(\__('If enabled the customer can choose to get an invoice. If automatic sending is enabled invoices will be issued only for these orders.', 'flexible-invoices'))->set_attribute('data-beacon_search', $checkout)->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('woocommerce_add_nip_field')->set_label(\__('Add VAT Number field to checkout', 'flexible-invoices'))->set_sublabel(\__('Enable', 'flexible-invoices'))->set_attribute('data-beacon_search', $checkout)->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('woocommerce_nip_label')->set_label(\__('Label', 'flexible-invoices'))->set_default_value(\__('VAT Number', 'flexible-invoices'))->add_class('nip-additional-fields hs-beacon-search')->set_attribute('data-beacon_search', $checkout), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('woocommerce_nip_placeholder')->set_label(\__('Placeholder', 'flexible-invoices'))->set_placeholder(\__('VAT Number', 'flexible-invoices'))->add_class('nip-additional-fields hs-beacon-search')->set_attribute('data-beacon_search', $checkout), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('woocommerce_nip_required')->set_label(\__('VAT Number field required', 'flexible-invoices'))->set_sublabel(\__('Enable', 'flexible-invoices'))->add_class('nip-additional-fields hs-beacon-search')->set_attribute('data-beacon_search', $checkout), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('woocommerce_validate_nip')->set_label(\__('Validate VAT Number', 'flexible-invoices'))->set_sublabel(\__('Enable', 'flexible-invoices'))->set_description(\__('VAT Number will have to be entered without hyphens, spaces and optionally can be prefixed with country code.', 'flexible-invoices'))->add_class('nip-additional-fields hs-beacon-search')->set_attribute('data-beacon_search', $checkout), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubEndField())->set_label('')];
    }
    /**
     * @inheritDoc
     */
    public static function get_tab_slug()
    {
        return 'checkout';
    }
    /**
     * @inheritDoc
     */
    public function get_tab_name()
    {
        return \__('Checkout', 'flexible-invoices');
    }
}
