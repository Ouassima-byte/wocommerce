<?php

namespace WPDesk\FlexibleInvoices\Settings\Documents;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields\DocumentsFieldsInterface;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubEndField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubStartField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\WPMLFieldDecorator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\Forms\Field\Header;
use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;
use WPDeskFIVendor\WPDesk\Forms\Field\SelectField;
use WPDeskFIVendor\WPDesk\Forms\Field\TextAreaField;

/**
 * Invoice Proforma Document Settings Sub Page.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields
 */
final class ProformaSettingsFields implements DocumentsFieldsInterface {

	/**
	 * @var SettingsStrategy
	 */
	private $strategy;

	/**
	 * @param SettingsStrategy $strategy
	 */
	public function __construct( SettingsStrategy $strategy ) {
		$this->strategy = $strategy;
	}

	/**
	 * @return string
	 */
	private function get_doc_link() {
		return sprintf(
			'<a href="%s" target="_blank">%s</a>',
			esc_url( get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/faktury-woocommerce/?utm_source=wp-admin-plugins&utm_medium=quick-link&utm_campaign=flexible-invoices-plugins-upgrade-link': 'https://www.flexibleinvoices.com/products/flexible-invoices-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=flexible-invoices-plugins-upgrade-link', array( 'https' )),
			esc_html__( 'To use Proforma Invoices buy PRO version of the plugin →', 'flexible-invoices' )
		);
	}

	/**
	 * @return array
	 */
	public function get_order_statuses() {
		$statuses = $this->strategy->get_order_statuses();
		unset( $statuses['completed'] );

		return $statuses;
	}

	/**
	 * @return string
	 */
	private function get_pro_class() {
		if ( ! InvoicesIntegration::is_pro() ) {
			return 'pro-version';
		}

		return '';
	}

    private function get_beacon_translations(): string {
        return 'Proforma Settings';
    }

    /**
     * @inheritDoc
     */
    public function get_fields() {
        $invoice_beacon = $this->get_beacon_translations();

        return [
            ( new SubStartField() )
                ->set_label( __( 'Proforma', 'flexible-invoices' ) )
                ->set_name( 'proforma' )
                ->add_class( $this->get_pro_class() ),

            ( new Header() )
                ->set_label( __( 'Proforma Invoice Settings', 'flexible-invoices' ) )
                ->set_description( $this->get_doc_link() ),

            ( new SelectField() )
                ->set_name( 'proforma_auto_create_status' )
                ->set_label( __( 'Issue proforma invoices automatically', 'flexible-invoices' ) )
                ->set_description( __( 'If you want to issue proforma invoices automatically, select order status. When the order status is changed to selected, a proforma invoice will be generated and a link to a PDF file will be attached to an e-mail.', 'flexible-invoices' ) )
                ->set_options(
                    $this->get_order_statuses()
                )
                ->add_class( 'hs-beacon-search' )
                ->set_attribute( 'data-beacon_search', $invoice_beacon )
	            ->set_disabled(),

            ( new InputTextField() )
                ->set_name( 'proforma_start_number' )
                ->set_label( __( 'Next Number', 'flexible-invoices' ) )
                ->set_default_value( 1 )
                ->set_attribute( 'type', 'number' )
                ->add_class( 'edit_disabled_field hs-beacon-search' )
                ->set_attribute( 'disabled', 'disabled' )
                ->set_description( __( 'Enter the next invoice number. The default value is 1 and changes every time an invoice is issued. Existing invoices won\'t be changed.', 'flexible-invoices' ) )
                ->set_attribute( 'data-beacon_search', $invoice_beacon )
	            ->set_disabled(),

            ( new WPMLFieldDecorator(
                ( new InputTextField() )
                    ->set_name( 'proforma_number_prefix' )
                    ->set_default_value( __( 'Invoice Proforma', 'flexible-invoices' ) )
                    ->set_label( __( 'Prefix', 'flexible-invoices' ) )
                    ->set_description( __( 'For prefixes use the following short tags: <code>{DD}</code> for day, <code>{MM}</code> for month, <code>{YYYY}</code> for year.', 'flexible-invoices' ) )
                    ->add_class( 'hs-beacon-search' )
                    ->set_attribute( 'data-beacon_search', $invoice_beacon )
	                ->set_disabled()
            ) )->get_field(),

            ( new WPMLFieldDecorator(
                ( new InputTextField() )
                    ->set_name( 'proforma_number_suffix' )
                    ->set_default_value( '/{MM}/{YYYY}' )
                    ->set_label( __( 'Suffix', 'flexible-invoices' ) )
                    ->set_description( __( 'For suffixes use the following short tags: <code>{DD}</code> for day, <code>{MM}</code> for month, <code>{YYYY}</code> for year.', 'flexible-invoices' ) )
                    ->add_class( 'hs-beacon-search' )
                    ->set_attribute( 'data-beacon_search', $invoice_beacon )
	                ->set_disabled()
            ) )->get_field(),

            ( new SelectField() )
                ->set_name( 'proforma_number_reset_type' )
                ->set_label( __( 'Number Reset', 'flexible-invoices' ) )
                ->set_description( __( 'Select when to reset the invoice number to 1.', 'flexible-invoices' ) )
                ->set_options(
                    [
                        'year'  => __( 'Yearly', 'flexible-invoices' ),
                        'month' => __( 'Monthly', 'flexible-invoices' ),
                        'none'  => __( 'None', 'flexible-invoices' ),
                    ]
                )
                ->add_class( 'hs-beacon-search' )
                ->set_attribute( 'data-beacon_search', $invoice_beacon )
	            ->set_disabled(),

            ( new InputTextField() )
                ->set_name( 'proforma_default_due_time' )
                ->set_default_value( 0 )
                ->set_attribute( 'type', 'number' )
                ->set_label( __( 'Default Due Time', 'flexible-invoices' ) )
                ->add_class( 'hs-beacon-search' )
                ->set_attribute( 'data-beacon_search', $invoice_beacon )
	            ->set_disabled(),

            ( new WPMLFieldDecorator(
                ( new TextAreaField() )
                    ->set_name( 'proforma_notes' )
                    ->set_label( __( 'Notes', 'flexible-invoices' ) )
                    ->add_class( 'large-text wide-input hs-beacon-search' )
                    ->set_attribute( 'data-beacon_search', $invoice_beacon )
	                ->set_disabled()
            ) )->get_field(),

            ( new SubEndField() )
                ->set_label( '' ),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function get_tab_slug() {
        return 'proforma';
    }

    /**
     * @inheritDoc
     */
    public function get_tab_name() {
        return __( 'Proforma', 'flexible-invoices' );
    }
}
