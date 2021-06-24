<?php

namespace WPDesk\FlexibleInvoices\Settings\WooCommerce;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields\SubTabInterface;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubEndField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubStartField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\Forms\Field\Header;
use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;
use WPDeskFIVendor\WPDesk\Forms\Field\SelectField;

/**
 * Invoice Proforma Document Settings Sub Page.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields
 */
final class GeneralSettingsFields implements SubTabInterface {

    /**
     * @return string
     */
    private function get_pro_class() {
        if ( ! InvoicesIntegration::is_pro() ) {
            return 'pro-version';
        }

        return '';
    }

    /**
     * @return string
     */
    private function get_doc_link() {
        $docs_link = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/docs/faktury-woocommerce-docs/' : 'https://docs.flexibleinvoices.com/';
        $docs_link .= '?utm_source=flexible-invoices-woocommerce-settings&utm_medium=link&utm_campaign=settings-docs-link';

        return sprintf(
            '<a href="%s" target="_blank">%s</a>',
            esc_url( $docs_link ),
            esc_html__( 'Read WooCommerce integration manual &rarr;', 'flexible-invoices' )
        );
    }

    /**
     * @return array
     */
    private function get_exchange_currencies() {
        $currencies_options = [];
        $currencies         = [
            'PLN' => 'PLN',
            'BGN' => 'BGN',
            'CZK' => 'CZK',
            'DKK' => 'DKK',
            'EUR' => 'EUR',
            'HRK' => 'HRK',
            'HUF' => 'HUF',
            'GBP' => 'GBP',
            'RON' => 'RON',
            'SEK' => 'SEK',
        ];
        foreach ( $currencies as $currency_code => $currency_name ) {
            $currencies_options[ $currency_code ] = $currency_name;
        }

        return $currencies_options;
    }

    /**
     * @inheritDoc
     */
    public function get_fields() {
        $general = 'Main Settings for WooCommerce';
        return [
            ( new SubStartField() )
                ->set_label( __( 'General', 'flexible-invoices' ) )
                ->set_name( 'general' ),

            ( new Header() )
                ->set_label( __( 'WooCommerce Settings', 'flexible-invoices' ) )
                ->set_description( $this->get_doc_link() ),

            ( new FICheckboxField() )
                ->set_name( 'woocommerce_sequential_orders' )
                ->set_label( __( 'Sequential Order Numbers', 'flexible-invoices' ) )
                ->set_sublabel( __( 'Enable', 'flexible-invoices' ) )
                ->set_description( __( 'In new stores, order numbers begin from 1. In existing stores numbers continue from the last order number.', 'flexible-invoices' ) )
                ->set_attribute( 'data-beacon_search', $general )
                ->add_class( 'hs-beacon-search' ),

            ( new SelectField() )
                ->set_name( 'woocommerce_date_of_sale' )
                ->set_label( __( 'Date of sale on the invoice', 'flexible-invoices' ) )
                ->set_description( __( 'Set which date will be the date of sale on the invoice.', 'flexible-invoices' ) )
                ->set_options(
                    [
                        'order_date'      => __( 'Use order date', 'flexible-invoices' ),
                        'order_completed' => __( 'Use order completed date', 'flexible-invoices' ),
                    ]
                )
                ->set_attribute( 'data-beacon_search', $general )
                ->add_class( 'hs-beacon-search' ),

            ( new FICheckboxField() )
                ->set_name( 'woocommerce_add_variant_info' )
                ->set_label( __( 'Variations', 'flexible-invoices' ) )
                ->set_sublabel( __( 'Add variations to invoices', 'flexible-invoices' ) )
                ->set_attribute( 'data-beacon_search', $general )
                ->add_class( 'hs-beacon-search' ),

            ( new FICheckboxField() )
                ->set_name( 'woocommerce_zero_invoice' )
                ->set_label( __( 'Free Orders', 'flexible-invoices' ) )
                ->set_sublabel( __( 'Do not automatically issue invoices for free orders', 'flexible-invoices' ) )
                ->set_attribute( 'data-beacon_search', $general )
                ->add_class( 'hs-beacon-search' ),

            ( new FICheckboxField() )
                ->set_name( 'woocommerce_zero_product' )
                ->set_label( __( 'Free line items', 'flexible-invoices' ) )
                ->set_sublabel( __( 'Do not add free line items to invoices (includes free products and free shipping)', 'flexible-invoices' ) )
                ->set_attribute( 'data-beacon_search', $general )
                ->add_class( 'hs-beacon-search ' . $this->get_pro_class() ),

            ( new FICheckboxField() )
                ->set_name( 'woocommerce_add_order_id' )
                ->set_label( __( 'Order number', 'flexible-invoices' ) )
                ->set_sublabel( __( 'Add order number to an invoice', 'flexible-invoices' ) )
                ->set_attribute( 'data-beacon_search', $general )
                ->add_class( 'hs-beacon-search' ),

            ( new FICheckboxField() )
                ->set_name( 'woocommerce_get_sku' )
                ->set_label( __( 'SKU', 'flexible-invoices' ) )
                ->set_sublabel( __( 'Use SKU numbers on invoices', 'flexible-invoices' ) )
                ->set_attribute( 'data-beacon_search', $general )
                ->add_class( 'hs-beacon-search' ),

            ( new FICheckboxField() )
                ->set_name( 'woocommerce_currency_exchange_enable' )
                ->set_label( __( 'Currency exchange table', 'flexible-invoices' ) )
                ->set_sublabel( __( 'Enable', 'flexible-invoices' ) )
                ->set_description( __( 'This option adds to the invoice a table with the conversion of the VAT value into local currency.', 'flexible-invoices' ) )
                ->add_class( 'hs-beacon-search ' . $this->get_pro_class() )
                ->set_attribute( 'data-beacon_search', $general ),

            ( new SelectField() )
                ->set_name( 'woocommerce_target_exchange_currency' )
                ->set_label( __( 'Exchange to currency', 'flexible-invoices' ) )
                ->set_description( __( 'Exchange rates are taken from European Central Bank table at the most recent exchange rate.', 'flexible-invoices' ) )
                ->set_options(
                    $this->get_exchange_currencies()
                )->add_class( 'exchange-table-fields hs-beacon-search' )
                ->set_attribute( 'data-beacon_search', $general )->set_disabled(),

            ( new SubEndField() )
                ->set_label( '' ),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function get_tab_slug() {
        return 'general';
    }

    /**
     * @inheritDoc
     */
    public function get_tab_name() {
        return __( 'General', 'flexible-invoices' );
    }
}
