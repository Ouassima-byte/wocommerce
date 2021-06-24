<?php

namespace WPDesk\FlexibleInvoices\Settings;

use WPDesk\FlexibleInvoices\Settings\WooCommerce\CheckoutSettingsFields;
use WPDesk\FlexibleInvoices\Settings\WooCommerce\GeneralSettingsFields;
use WPDesk\FlexibleInvoices\Settings\WooCommerce\MossSettingsFields;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Replace default WooCommerce settings with tabs for free version of plugin.
 *
 * @package WPDesk\FlexibleInvoices\Settings
 */
class WooCommerceSettingsTabsReplacer implements Hookable {

	/**
	 * @var SettingsStrategy
	 */
	private $settings_strategy;

	/**
	 * @param SettingsStrategy $settings_strategy
	 */
	public function __construct( SettingsStrategy $settings_strategy ) {
		$this->settings_strategy = $settings_strategy;
	}

	/**
	 * Fires hooks.
	 */
	public function hooks() {
		add_filter( 'fi/core/settings/woocommerce', [ $this, 'inject_documents_settings' ] );
	}

	/**
	 * @param array $settings
	 *
	 * @return array
	 */
	public function inject_documents_settings( $settings ) {
		foreach ( $settings as $key => $object ) {
			switch ( $key ) {
				case 'general':
					$documents_settings[ $key ] = new GeneralSettingsFields();
					break;
				case 'moss':
					$documents_settings[ $key ] = new MossSettingsFields();
					break;
				default:
					$documents_settings[ $key ] = $object;
					break;
			}
		}

		return $documents_settings;
	}

}
