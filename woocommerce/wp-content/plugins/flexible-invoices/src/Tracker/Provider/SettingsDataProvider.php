<?php

namespace WPDesk\FlexibleInvoices\Tracker\Provider;

/**
 * Provides info about FI Pro settings.
 *
 * @package WPDesk\FlexibleInvoices\Tracker\Provider
 */
class SettingsDataProvider implements \WPDesk_Tracker_Data_Provider {

	/**
	 * @inheritDoc
	 */
	public function get_data() {
		$settings_container = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings();

		$plugin_data = [
			'woocommerce_sequential_orders'        => $settings_container->get( 'woocommerce_sequential_orders' ),
			'woocommerce_date_of_sale'             => $settings_container->get( 'woocommerce_date_of_sale' ),
			'woocommerce_add_variant_info'         => $settings_container->get( 'woocommerce_add_variant_info' ),
			'woocommerce_zero_invoice'             => $settings_container->get( 'woocommerce_zero_invoice' ),
			'woocommerce_zero_product'             => $settings_container->get( 'woocommerce_zero_product' ),
			'woocommerce_add_order_id'             => $settings_container->get( 'woocommerce_add_order_id' ),
			'woocommerce_get_sku'                  => $settings_container->get( 'woocommerce_get_sku' ),
			'woocommerce_currency_exchange_enable' => $settings_container->get( 'woocommerce_currency_exchange_enable' ),
			'woocommerce_target_exchange_currency' => $settings_container->get( 'woocommerce_target_exchange_currency' ),
			'woocommerce_add_invoice_ask_field'    => $settings_container->get( 'woocommerce_add_invoice_ask_field' ),
			'woocommerce_add_nip_field'            => $settings_container->get( 'woocommerce_add_nip_field' ),
			'woocommerce_nip_required'             => $settings_container->get( 'woocommerce_nip_required' ),
			'woocommerce_validate_nip'             => $settings_container->get( 'woocommerce_validate_nip' ),
			'woocommerce_eu_vat_vies_validate'     => $settings_container->get( 'woocommerce_eu_vat_vies_validate' ),
			'woocommerce_eu_vat_failure_handling'  => $settings_container->get( 'woocommerce_eu_vat_failure_handling' ),
			'woocommerce_moss_tax_classes'         => $settings_container->get( 'woocommerce_moss_tax_classes' ),
			'woocommerce_moss_validate_ip'         => $settings_container->get( 'woocommerce_moss_validate_ip' ),
			'invoice_auto_paid_status'             => $settings_container->get( 'invoice_auto_paid_status' ),
			'invoice_auto_create_status'           => $settings_container->get( 'invoice_auto_create_status' ),
			'invoice_start_number'                 => $settings_container->get( 'invoice_start_number' ),
			'invoice_number_suffix'                => $settings_container->get( 'invoice_number_suffix' ),
			'invoice_number_reset_type'            => $settings_container->get( 'invoice_number_reset_type' ),
			'invoice_default_due_time'             => $settings_container->get( 'invoice_default_due_time' ),
			'pro'                                  => 0,
		];

		return [ 'flexible_invoices' => $plugin_data ];
	}

}
