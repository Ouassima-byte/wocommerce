<?php

namespace WPDesk\FlexibleInvoices\RateNotice;

use WPDeskFIVendor\WPDesk\Notice\Notice;
use WPDeskFIVendor\WPDesk\Notice\PermanentDismissibleNotice;
use WPDeskFIVendor\WPDesk\Persistence\Adapter\WordPress\WordpressOptionsContainer;
use WPDeskFIVendor\WPDesk\Persistence\PersistentContainer;
use WPDeskFIVendor\WPDesk\ShowDecision\ShouldShowStrategy;


/**
 * Two weeks notice defined in issue #90.
 *
 * @package WPDesk\ShopMagic\Admin\RateNotice
 */
class TwoWeeksNotice {
	const NOTICE_NAME = 'flexible_invoices_two_week_rate_notice';
	const CLOSE_TEMPORARY_NOTICE = 'close-temporary-notice-date';

	const PLUGIN_ACTIVATION_DATE_OPTION   = 'plugin_activation_flexible-invoices/flexible-invoices.php';
	const PERSISTENT_KEY_NEVER_SHOW_AGAIN = 'two-weeks-permanent';
	const PERSISTENT_KEY_LAST_TIME_HIDDEN = 'two-weeks-last-date';

	/** @var string */
	private $assets_url;

	/** @var PersistentContainer */
	private $persistence;

	/**
	 * Current time.
	 *
	 * @var int
	 */
	private $now;

	/** @var ShouldShowStrategy */
	private $show_strategy;

	/**
	 * @param string $assets_url
	 * @param ShouldShowStrategy $show_strategy Show on the pages in which Beacon is visible.
	 * @param int|null $now Current time in unix.
	 */
	public function __construct(
		$assets_url,
		ShouldShowStrategy $show_strategy,
		$now = null
	) {
		$this->assets_url = $assets_url;
		if ( $now === null ) {
			$this->now = time();
		} else {
			$this->now = $now;
		}
		$this->show_strategy = $show_strategy;
		$this->persistence   = new WordpressOptionsContainer( 'flexible_invoices-notice' );
	}

	public function hooks() {
		add_action( 'admin_enqueue_scripts', function () {
			wp_enqueue_script( 'flexible_invoices-rate-notice', $this->assets_url . '/js/two-weeks-notice.js' );
		} );
		add_action( 'wp_ajax_flexible_invoices_close_temporary', function () {
			if ( $this->persistence->has( 'two-weeks-last-date' ) ) {
				$this->persistence->set( self::PERSISTENT_KEY_NEVER_SHOW_AGAIN, true );
			} else {
				$this->persistence->set( self::PERSISTENT_KEY_LAST_TIME_HIDDEN, time() );
			}
		} );
	}

	/**
	 * Action links
	 *
	 * @return string[]
	 */
	private function action_links() {
		$actions[] = sprintf(
			__( '%1$sSure, it\'s worth it!%2$s', 'flexible-invoices' ),
			'<a target="_blank" href="' . esc_url( 'https://wpde.sk/flexible-invoices-review' ) . '">',
			'</a>'
		);
		$actions[] = sprintf(
			__( '%1$sNope, maybe later%2$s', 'flexible-invoices' ),
			'<a data-type="date" class="sm-close-temporary-notice" data-source="' . self::CLOSE_TEMPORARY_NOTICE . '" href="#">',
			'</a>'
		);
		$actions[] = sprintf(
			__( '%1$sNo, never!%2$s', 'flexible-invoices' ),
			'<a class="close-rate-notice notice-dismiss-link" data-source="already-did" href="#">',
			'</a>'
		);

		return $actions;
	}

	/**
	 * Should show message
	 *
	 * @return bool
	 */
	public function should_show_message() {
		if ( time() > strtotime( '2020-04-01' ) ) {
			if ( $this->persistence->has( self::PERSISTENT_KEY_NEVER_SHOW_AGAIN ) ) {
				return false;
			}

			if ( $this->show_strategy->shouldDisplay() ) {

				/** @var string $activation_date */
				$activation_date = get_option( self::PLUGIN_ACTIVATION_DATE_OPTION );
				$two_weeks       = 60 * 60 * 24 * 7 * 2;

				if ( ! empty( $activation_date ) && strtotime( $activation_date ) + $two_weeks < $this->now ) {

					if ( $this->persistence->has( self::PERSISTENT_KEY_LAST_TIME_HIDDEN ) ) {
						$last_close = (int) $this->persistence->get( self::PERSISTENT_KEY_LAST_TIME_HIDDEN );
						return ! empty( $last_close ) && $last_close + $two_weeks < $this->now;
					}
					return true;

				}

			}

		}

		return false;
	}

	/**
	 * Show admin notice
	 *
	 * @return void
	 */
	public function show_message() {
		new PermanentDismissibleNotice(
			$this->get_message(),
			self::NOTICE_NAME,
			Notice::NOTICE_TYPE_INFO,
			10,
			array(
				'class' => self::NOTICE_NAME,
				'id'    => self::NOTICE_NAME,
			)
		);
	}

	/**
	 * Get rate message
	 *
	 * @return string
	 */
	private function get_message() {
		$message = __( 'Amazing! You\'ve been using Flexible PDF Invoices for WordPress for two weeks. I hope it meets your expectations! If so, may I ask you for a big favor and a 5-star rating on the plugin\'s site?',
			'flexible-invoices' );
		$message .= '<br/>';
		$message .= implode( ' | ', $this->action_links() );

		return $message;
	}

}
