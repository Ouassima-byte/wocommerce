<?php

namespace WPDesk\FlexibleInvoices\Settings;

use WPDesk\FlexibleInvoices\Settings\Documents\CorrectionSettingsFields;
use WPDesk\FlexibleInvoices\Settings\Documents\ProformaSettingsFields;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Replace default document settings with tabs for free version of plugin.
 *
 * @package WPDesk\FlexibleInvoices\Settings
 */
class DocumentsSettingsTabsReplacer implements Hookable {

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
		add_filter( 'fi/core/settings/documents', [ $this, 'inject_documents_settings' ] );
	}

	/**
	 * @param array $settings
	 *
	 * @return array
	 */
	public function inject_documents_settings( $settings ) {
		foreach ( $settings as $key => $object ) {
			switch ( $key ) {
				case 'proforma':
					$documents_settings[ $key ] = new ProformaSettingsFields( $this->settings_strategy );
					break;
				case 'correction':
					$documents_settings[ $key ] = new CorrectionSettingsFields();
					break;
				default:
					$documents_settings[ $key ] = $object;
					break;
			}
		}

		return $documents_settings;
	}

}
