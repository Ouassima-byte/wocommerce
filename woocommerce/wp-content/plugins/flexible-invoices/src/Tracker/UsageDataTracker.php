<?php

namespace  WPDesk\FlexibleInvoices\Tracker;

/**
 * Tracks data about usages.
 *
 * @package WPDesk\ShopMagic\Tracker
 */
class UsageDataTracker {
	/** @var string */
	private $plugin_file_name;

	public function __construct( $plugin_file_name ) {
		$this->plugin_file_name = $plugin_file_name;
	}

	public function hooks() {
		$tracker_factory = new \WPDesk_Tracker_Factory();
		/** @var \WPDesk_Tracker_Interface $tracker */
		$tracker = $tracker_factory->create_tracker( $this->plugin_file_name );

		$tracker->add_data_provider( new Provider\SettingsDataProvider() );

		add_filter( 'wpdesk_tracker_enabled', function () {
			return true;
		} );
	}
}
