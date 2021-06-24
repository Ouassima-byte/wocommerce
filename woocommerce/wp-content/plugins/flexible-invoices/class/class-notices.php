<?php

use WPDeskFIVendor\WPDesk\Notice\Notice;

class InvoicesNotices {

	const POST_TYPE_NAME = 'inspire_invoice';

	/**
	 * Fires hooks.
	 */
	public function hooks() {
		add_action( 'admin_init', [ $this, 'add_duplicates_notice_error' ] );
	}

	/**
	 * @return void
	 */
	public function add_duplicates_notice_error() {
		if ( $this->has_duplicates() ) {
			$notice = new Notice( sprintf( __( '<strong>Warning!</strong> There are documents with the same number in the invoice list. Check <a href="%s">here</a>.', 'flexible-invoices' ), admin_url( 'edit.php?post_type=' . self::POST_TYPE_NAME .'&filter=show_duplicated' ) ), Notice::NOTICE_TYPE_ERROR, true );
		}
	}

	/**
	 * @return int
	 */
	private function has_duplicates() {
		global $wpdb;

		return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(post_title) FROM $wpdb->posts WHERE `post_type` = %s AND `post_status` = 'publish' GROUP BY `post_title` HAVING COUNT(post_title) > 1", self::POST_TYPE_NAME ) );
	}

}
