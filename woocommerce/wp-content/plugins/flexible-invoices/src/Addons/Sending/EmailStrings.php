<?php

namespace WPDesk\FlexibleInvoices\Addons\Sending;

/**
 * Define default email strings.
 *
 * @package WPDesk\FIS\Email
 */
class EmailStrings {

	/**
	 * @return string
	 */
	public static function get_email_report_subject(): string {
		return __( 'Report from {from_date} to {to_date} ', 'flexible-invoices' );
	}

	/**
	 * @return string
	 */
	public static function get_email_report_body(): string {
		//phpcs:disable
		return __( 'Please find attached report included sales from {from_date} to {to_date}.', 'flexible-invoices' ) . PHP_EOL . PHP_EOL .
		       __( '<strong>Sales from the shop:</strong> {site_title}', 'flexible-invoices' ) . PHP_EOL . PHP_EOL .
		       __( '<strong>Additional report details:</strong>', 'flexible-invoices' ) . PHP_EOL .
		       __( 'Shop website: {site_url},', 'flexible-invoices' ) . PHP_EOL .
		       __( 'Shop email: {admin_email},', 'flexible-invoices' ) . PHP_EOL . PHP_EOL .
		       __( 'Greetings,', 'flexible-invoices' ) . PHP_EOL .
		       __( 'Shop Support {site_title},', 'flexible-invoices' );
		//phpcs:enable
	}

	/**
	 * @return string
	 */
	public static function get_email_invoice_subject(): string {
		return __( 'Invoices from {from_date} to {to_date} ', 'flexible-invoices' );
	}

	/**
	 * @return string
	 */
	public static function get_email_invoice_body(): string {
		//phpcs:disable
		return __( 'Please find attached sales invoices from {from_date} to {to_date}.', 'flexible-invoices' ) . PHP_EOL . PHP_EOL .
		       __( '<strong>Sales from the shop:</strong> {site_title}', 'flexible-invoices' ) . PHP_EOL . PHP_EOL .
		       __( '<strong>Additional invoice details:</strong>', 'flexible-invoices' ) . PHP_EOL .
		       __( 'Shop website: {site_url},', 'flexible-invoices' ) . PHP_EOL .
		       __( 'Shop email: {admin_email},', 'flexible-invoices' ) . PHP_EOL . PHP_EOL .
		       __( 'Greetings,', 'flexible-invoices' ) . PHP_EOL .
		       __( 'Shop Support {site_title},', 'flexible-invoices' );
		//phpcs:enable
	}

}
