<?php
/**
 * This class is defined only for backward compatibility.
 */
if( ! class_exists( 'InvoicePost') ) {
	class InvoicePost {

		public static $instance;

		private function __construct() {}

		public static function getInstance() {
			if ( self::$instance == false ) {
				self::$instance = new InvoicePost();
			}
			return self::$instance;
		}
	}
}

