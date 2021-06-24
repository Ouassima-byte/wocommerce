<?php

namespace WPDesk\FlexibleInvoices\Addons\Sending;

use WPDesk\FlexibleInvoices\Addons\Sending\Fields\MultipleInputTextField;
use WPDesk\FlexibleInvoices\Addons\Sending\Fields\WysiwygField;
use WPDeskFIVendor\WPDesk\Forms\Field\CheckboxField;
use WPDeskFIVendor\WPDesk\Forms\Field\Header;
use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;
use WPDeskFIVendor\WPDesk\Forms\Field\SelectField;
use WPDeskFIVendor\WPDesk\Forms\Field\SubmitField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\FieldSettingsTab;

class SendingTab extends FieldSettingsTab {

	const TAX_NAME = 'name';
	const TAX_RATE = 'rate';

	/**
	 * Get disabled data value.
	 *
	 * @return string
	 */
	private function get_disabled(): string {
		return 'yes';
	}

	/**
	 * Field definition.
	 *
	 * @return array
	 */
	protected function get_fields() {
		$bundle_link = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/pakiet-faktury/?utm_source=wp-admin-plugins&utm_medium=button&utm_campaign=flexible-invoices-bundle' : 'https://flexibleinvoices.com/?utm_source=wp-admin-plugins&utm_medium=button&utm_campaign=flexible-invoices-bundle';

		return [
			( new Header() )
				->set_name( 'send_document_heading' )
				->set_description( sprintf( '<a target="_blank" href="%1$s" >%2$s</a>', $bundle_link, esc_html__( 'Buy PRO bundle including all addons &rarr;', 'flexible-invoices' ) ) )
				->set_label( __( 'Sending invoices', 'flexible-invoices' ) )
				->set_disabled(),
			( new CheckboxField() )
				->set_name( 'enable_sending_to_customer' )
				->set_label( __( 'Sending invoices to customers', 'flexible-invoices' ) )
				->set_default_value( 'on' )
				->set_sublabel( __( 'Enable automatic mailing of invoices to customers', 'flexible-invoices' ) )
				->set_disabled(),
			( new CheckboxField() )
				->set_name( 'attach_document_to_email' )
				->set_label( __( 'Attachments in the e-mail', 'flexible-invoices' ) )
				->set_sublabel( __( 'Attach PDF file to invoice email', 'flexible-invoices' ) )
				->set_disabled(),
			( new Header() )
				->set_name( 'send_document_heading' )
				->set_label( __( 'Cyclical sending of invoices', 'flexible-invoices' ) )
				->set_description( __( 'Below you will set up a cyclical sending of ZIP files with invoices. You can find out more in the <a href="https://wpde.sk/fi-sending-docs" target="_blank" rel="nofollow, noopener">plugins docs</a>.', 'flexible-invoices' ) )
				->set_disabled(),
			( new MultipleInputTextField() )
				->set_name( 'fias_document_additional_recipient' )
				->set_label( __( 'Additional recipients', 'flexible-invoices' ) )
				->set_placeholder( __( 'E-mail address', 'flexible-invoices' ) )
				->set_description( __( 'Add additional recipients', 'flexible-invoices' ) )
				->set_disabled(),
			( new SelectField() )
				->set_label( __( 'Schedule for sending documents', 'flexible-invoices' ) )
				->set_name( 'fias_document_sending_type' )
				->set_description( __( 'Choose the period for which you want sent documents to the address from the "Additional Recipients" setting.', 'flexible-invoices' ) )
				->set_options(
					[
						'none'    => __( 'none', 'flexible-invoices' ),
						'daily'   => __( 'daily', 'flexible-invoices' ),
						'weekly'  => __( 'weekly', 'flexible-invoices' ),
						'monthly' => __( 'monthly', 'flexible-invoices' ),
					]
				)
				->set_default_value( 'none' )
				->set_disabled(),
			( new InputTextField() )
				->set_name( 'fias_document_mail_subject' )
				->set_label( __( 'Email subject', 'flexible-invoices' ) )
				->set_placeholder( __( 'Invoices from {from_date} to {to_date}', 'flexible-invoices' ) )
				->set_default_value( EmailStrings::get_email_invoice_subject() )
				->set_description( __( 'You can use the following shortcodes: {site_title}, {site_url}, {admin_email}, {current_date}, {site_description}, {from_date}, {to_date}.', 'flexible-invoices' ) )
				->set_disabled(),
			( new WysiwygField() )
				->set_name( 'fias_document_mail_body' )
				->set_label( __( 'E-mail body', 'flexible-invoices' ) )
				->set_default_value( EmailStrings::get_email_invoice_body() )
				->set_description( __( 'You can use the following shortcodes: {site_title}, {site_url}, {admin_email}, {current_date}, {site_description}, {from_date}, {to_date}.', 'flexible-invoices' ) )
				->set_disabled(),

			( new Header() )
				->set_name( 'send_report_heading' )
				->set_label( __( 'Cyclical sending of reports', 'flexible-invoices' ) )
				->set_description( __( 'Below you will set up a cyclical sending of reports. You can find out more in the <a href="https://wpde.sk/fi-sending-docs" target="_blank" rel="nofollow, noopener">plugins docs</a>.', 'flexible-invoices' ) )
				->set_disabled(),
			( new MultipleInputTextField() )
				->set_name( 'fias_report_additional_recipient' )
				->set_label( __( 'Additional recipients', 'flexible-invoices' ) )
				->set_placeholder( __( 'E-mail address', 'flexible-invoices' ) )
				->set_description( __( 'Add additional recipients.', 'flexible-invoices' ) )
				->set_attribute( 'data-disabled', $this->get_disabled() )
				->set_disabled(),
			( new SelectField() )
				->set_label( __( 'Schedule for sending reports', 'flexible-invoices' ) )
				->set_name( 'fias_report_sending_type' )
				->set_description( __( 'Choose the period for which you want the report automatically sent to the address from the "Additional Recipients" setting.', 'flexible-invoices' ) )
				->set_options(
					[
						'none'    => __( 'none', 'flexible-invoices' ),
						'daily'   => __( 'daily', 'flexible-invoices' ),
						'weekly'  => __( 'weekly', 'flexible-invoices' ),
						'monthly' => __( 'monthly', 'flexible-invoices' ),
					]
				)
				->set_default_value( 'none' )
				->set_attribute( 'data-disabled', $this->get_disabled() )
				->set_disabled(),
			( new InputTextField() )
				->set_name( 'fias_report_mail_subject' )
				->set_label( __( 'Email subject', 'flexible-invoices' ) )
				->set_placeholder( __( 'Report from {from_date} to {to_date} ', 'flexible-invoices' ) )
				->set_default_value( EmailStrings::get_email_report_subject() )
				->set_description( __( 'You can use the following shortcodes: {site_title}, {site_url}, {admin_email}, {current_date}, {site_description}, {from_date}, {to_date}.', 'flexible-invoices' ) )
				->set_attribute( 'data-disabled', $this->get_disabled() )
				->set_disabled(),
			( new WysiwygField() )
				->set_name( 'fias_report_mail_body' )
				->set_label( __( 'E-mail body', 'flexible-invoices' ) )
				->set_description( __( 'You can use the following shortcodes: {site_title}, {site_url}, {admin_email}, {current_date}, {site_description}, {from_date}, {to_date}.', 'flexible-invoices' ) )
				->set_default_value( EmailStrings::get_email_report_body() )
				->set_attribute( 'data-disabled', $this->get_disabled() )
				->set_disabled(),
			( new SubmitField() )
				->set_name( 'fias_report_additional_recipient' )
				->set_label( __( 'Save changes', 'flexible-invoices' ) )
				->add_class( 'button-primary' )
				->set_disabled(),
		];
	}

	/**
	 * Get tab slug.
	 *
	 * @return string
	 */
	public static function get_tab_slug() {
		return 'fias-sending';
	}

	/**
	 * Get tab name.
	 *
	 * @return string
	 */
	public function get_tab_name() {
		return __( 'Advanced Sending', 'flexible-invoices' );
	}

	/**
	 * Is active.
	 *
	 * @return bool
	 */
	public static function is_active() {
		return true;
	}

}
