<?php

namespace WPDesk\FlexibleInvoices\Addons\Sending;

use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Resolver\DirResolver;

class SettingsIntegration implements Hookable {

	const TAB_NAME = 'sending';

	/**
	 * @var string
	 */
	private $plugin_path;

	/**
	 * @var string
	 */
	private $plugin_url;

	/**
	 * @param string $plugin_path Plugin path.
	 * @param string $plugin_url  Plugin URL.
	 */
	public function __construct( string $plugin_path, string $plugin_url ) {
		$this->plugin_path = $plugin_path;
		$this->plugin_url  = $plugin_url;
	}

	/**
	 * Settings constructor.
	 */
	public function hooks() {
		if ( ! $this->is_plugin_active( 'flexible-invoices-sending/flexible-invoices-sending.php' ) ) {
			add_filter( 'fi/core/settings/settings_template_resolvers', [ $this, 'add_settings_template_resolver' ] );
			add_action( 'fi/core/settings/tabs', [ $this, 'register_settings' ] );
			add_filter( 'teeny_mce_before_init', [ $this, 'teeny_mce_before_init' ], 100, 2 );
			add_editor_style( $this->plugin_url . '/assets/css/editor.css' );
		}
	}

	/**
	 * @param string $plugin
	 *
	 * @return bool
	 */
	private function is_plugin_active( string $plugin ) {
		if ( function_exists( 'is_plugin_active_for_network' ) ) {
			if ( is_plugin_active_for_network( $plugin ) ) {
				return true;
			}
		}

		return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
	}

	/**
	 * Register settings tab.
	 *
	 * @param array $tabs Tabs.
	 *
	 * @return array
	 */
	public function register_settings( array $tabs ): array {
		$tabs[ SendingTab::get_tab_slug() ] = new SendingTab();

		return $tabs;
	}

	/**
	 * Add settings template resolver.
	 *
	 * @param array $resolvers Resolvers.
	 *
	 * @return array
	 */
	public function add_settings_template_resolver( array $resolvers ): array {
		$resolvers[] = new DirResolver( $this->plugin_path . '/templates/addons/fields' );

		return $resolvers;
	}

	/**
	 * Disable TinyMCE settings field if reports is disabled.
	 *
	 * @param array  $args Editor args.
	 * @param string $id   Editor ID.
	 *
	 * @return array
	 */
	public function teeny_mce_before_init( array $args, string $id ): array {
		if ( $id === 'wyswig_fias_report_mail_body' || $id === 'wyswig_fias_document_mail_body' ) {
			$args['readonly']   = 1;
			$args['body_class'] = 'body-disabled';
		}

		return $args;
	}

}
