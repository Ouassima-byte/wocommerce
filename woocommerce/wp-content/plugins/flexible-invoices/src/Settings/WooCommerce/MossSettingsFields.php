<?php

namespace WPDesk\FlexibleInvoices\Settings\WooCommerce;

use WC_Tax;
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
final class MossSettingsFields implements SubTabInterface {

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
     * @return array
     */
    private function get_woocommerce_tax_classes() {
        $tax_classes                 = WC_Tax::get_tax_classes();
        $classes_options             = array();
        $classes_options['standard'] = __( 'Standard', 'flexible-invoices' );
        foreach ( $tax_classes as $class ) {
            $classes_options[ sanitize_title( $class ) ] = esc_html( $class );
        }

        return $classes_options;
    }

    /**
     * @return string
     */
    private function get_moss_link() {
        return
            esc_url(
                get_locale() === 'pl_PL'
                    ? 'https://www.wpdesk.pl/docs/faktury-woocommerce-docs/?utm_source=wp-admin-plugins&utm_medium=quick-link&utm_campaign=flexible-invoices-docs-link#moss'
                    : 'https://docs.flexibleinvoices.com/article/813-moss?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-invoices-docs-link'
                , array( 'https' )
            );
    }

    /**
     * @return array|\WPDesk\Forms\Field[]
     */
    public function get_fields() {

        $moss = 'MOSS';
        return [
            ( new SubStartField() )
                ->set_label( __( 'MOSS', 'flexible-invoices' ) )
                ->set_name( 'moss' )
                ->add_class( $this->get_pro_class() ),

            ( new Header() )
                ->set_label( __( 'MOSS Handling', 'flexible-invoices' ) )
                ->set_description( sprintf( __( 'EU VAT laws for digital goods affect B2C transactions. From 2015 VAT on digital goods must be calculated based on the customer location, and you need to collect evidence of this (IP address and Billing Address). B2B transactions are subject to reverse charge. <a href="%s" target="_blank">Read this guide</a> for instructions on doing this.', 'flexible-invoices' ), $this->get_moss_link() ) ),

            ( new FICheckboxField() )
                ->set_name( 'woocommerce_eu_vat_vies_validate' )
                ->set_label( __( 'VIES Validation', 'flexible-invoices' ) )
                ->set_sublabel( __( 'Enable', 'flexible-invoices' ) )
                ->add_class( 'hs-beacon-search' )
                ->set_attribute( 'data-beacon_search', $moss )
	            ->set_disabled(),

            ( new SelectField() )
                ->set_name( 'woocommerce_eu_vat_failure_handling' )
                ->set_label( __( 'Failed Validation Handling', 'flexible-invoices' ) )
                ->set_options(
                    [
                        'reject'             => __( 'Reject the order and show the customer an error message.', 'flexible-invoices' ),
                        'accept_with_vat'    => __( 'Accept the order, but do not remove VAT.', 'flexible-invoices' ),
                        'accept_without_vat' => __( 'Accept the order and remove VAT.', 'flexible-invoices' ),
                    ]
                )
                ->add_class( 'vies-validation-fields hs-beacon-search' )
                ->set_attribute( 'data-beacon_search', $moss )
	            ->set_disabled(),

            ( new SelectField() )
                ->set_name( 'woocommerce_moss_tax_classes' )
                ->set_label( __( 'Tax class for MOSS', 'flexible-invoices' ) )
                ->set_description( __( 'Select the tax classes that the plugin shall use to handling the MOSS.', 'flexible-invoices' ) )
                ->set_options(
                    $this->get_woocommerce_tax_classes()
                )
                ->set_multiple()
                ->add_class( 'select2 vies-validation-fields hs-beacon-search' )
                ->set_attribute( 'data-beacon_search', $moss )
	            ->set_disabled(),

            ( new FICheckboxField() )
                ->set_name( 'woocommerce_moss_validate_ip' )
                ->set_label( __( 'Collect and Validate Evidence', 'flexible-invoices' ) )
                ->set_sublabel( __( 'Enable', 'flexible-invoices' ) )
                ->set_description( __( 'Option validates the customer IP address against their billing address, and prompts the customer to self-declare their address if they do not match.', 'flexible-invoices' ) )
                ->add_class( 'vies-validation-fields hs-beacon-search' )
                ->set_attribute( 'data-beacon_search', $moss )
	            ->set_disabled(),


            ( new InputTextField() )
                ->set_name( 'woocommerce_reverse_charge_description' )
                ->set_label( __( 'Reverse charge description', 'flexible-invoices' ) )
                ->set_default_value( __( 'Reverse charge', 'flexible-invoices' ) )
                ->add_class( 'vies-validation-fields hs-beacon-search' )
                ->set_attribute( 'data-beacon_search', $moss )
	            ->set_disabled(),


            ( new InputTextField() )
                ->set_name( 'woocommerce_vat_moss_description' )
                ->set_label( __( 'VAT MOSS rate description', 'flexible-invoices' ) )
                ->add_class( 'vies-validation-fields hs-beacon-search' )
                ->set_attribute( 'data-beacon_search', $moss )
	            ->set_disabled(),

            ( new SubEndField() )
                ->set_label( '' ),
        ];
    }

    /**
     * @return string
     */
    public static function get_tab_slug() {
        return 'moss';
    }

    /**
     * @return string
     */
    public function get_tab_name() {
        return __( 'MOSS', 'flexible-invoices' );
    }
}
