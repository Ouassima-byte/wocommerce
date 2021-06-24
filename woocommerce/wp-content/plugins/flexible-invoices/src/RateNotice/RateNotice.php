<?php

namespace WPDesk\FlexibleInvoices\RateNotice;

/**
 * Manages greetings&marketing notices.
 *
 * @package WPDesk\ShopMagic\Admin\RateNotice
 */
class RateNotice {

	/**
	 * @var TwoWeeksNotice[]
	 */
	private $notices;

	/**
	 * @param TwoWeeksNotice[] $notices
	 */
	public function __construct( array $notices ) {
		$this->notices = $notices;
	}

	public function hooks() {
		foreach ( $this->notices as $notice ) {
			$notice->hooks();
			add_action( 'admin_init', static function () use ( $notice ) {
				if ( $notice->should_show_message() ) {
					$notice->show_message();
				}
			} );
		}
	}
}
