<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

ini_set('user_agent', 'mpdf');

class Invoice {

	private static $_oInstance = false;

	private $script_version = '9';

	const PLUGIN_NAMESPACE = 'inspire_invoices';

	protected $_pluginNamespace = 'inspire_invoices';
	protected $_textDomain = 'flexible-invoices';

	protected $_templatePath = 'templates/flexible-invoices';

	//protected $_invoicesAdmin;
	public $invoicePostType;
	/** @var invoiceSettings */
	protected $settings;

	static $plugin;

	/**
	 * @var string
	 */
	private $_pluginPath;

	private $_plugin;

	public function __construct() {
		$this->_plugin = self::$plugin->get_plugin_info();
		$this->_initBaseVariables();

		$this->settings = new invoiceSettings( $this );
		$this->capabilities = new invoicePostTypeCapabilities( $this->settings );
		$this->invoicePostType = new invoicePostType( $this, $this->capabilities );

		$this->invoiceUser = new invoiceUser($this);

		// load locales
		load_plugin_textdomain( 'flexible-invoices', FALSE, dirname( plugin_basename(__FILE__) ) . '/lang/' );


		add_action( 'init', array( $this, 'init' ) );

		// Activate
		register_activation_hook( __FILE__, array( $this, 'pluginActivated' ) );

		// Templates Path
		$this->_templatePath = 'flexible-invoices';

		// invoice numbering actions and filters
		add_filter( 'pre_option_' . $this->_pluginNamespace . '_order_start_invoice_number', array( $this, 'pre_option_inspire_invoices_order_start_invoice_number' ), 10, 2 );
		add_filter( 'option_' . $this->_pluginNamespace . '_order_start_invoice_number', array( $this, 'option_inspire_invoices_start_invoice_number' ), 10, 2 );
		add_filter( 'option_' . $this->_pluginNamespace . '_correction_start_invoice_number', array( $this, 'option_inspire_invoices_start_invoice_number' ), 10, 2 );
	}

	public function pre_option_inspire_invoices_order_start_invoice_number( $ret, $option ) {
		/*
		global $wpdb;
		$wpdb->query(
			'LOCK TABLES ' . $wpdb->options .' WRITE, '
			. $wpdb->posts . ' WRITE, '
			. $wpdb->postmeta . ' WRITE, '
			. $wpdb->prefix . 'woocommerce_order_items WRITE '
		);
		//$wpdb->query( 'FLUSH TABLES WITH READ LOCK' );
		*/
		return $ret;
	}

	public function option_inspire_invoices_start_invoice_number( $value, $option ) {
		global $wpdb;
		$wpdb->query(
			$wpdb->prepare( "UPDATE $wpdb->options SET option_value = option_value WHERE option_name = %s",	$option	)
		);
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $option ) );
		if ( is_object( $row ) ) {
			$value = $row->option_value;
		}
		return $value;
	}

	public function loadTemplate($name, $path = '', $args = array()) {
		$path = trim( $path, '/') ;
		$template_name = implode( '/', apply_filters( 'flexible_invoices_template_location', [ get_stylesheet_directory(), $this->getTemplatePath(), $path, $name . '.php' ] ) );
		if ( ! file_exists( $template_name ) ) {
			$template_name = implode( '/', array($this->_pluginPath, 'class/templates', $path, $name . '.php' ) );
		}

		ob_start();
		include($template_name);
		return ob_get_clean();
	}



	public function init() {
		if ( !get_option( 'inspire_invoices_payment_name_updated' ) ) {

			$invoices = get_posts( array( 'post_type' => 'inspire_invoice', 'post_status' => 'any', 'posts_per_page' => -1 ) );

			$payment_methods = array(
				'transfer' => __('Bank transfer', 'flexible-invoices'),
				'cash' => __('Cash', 'flexible-invoices'),
				'orher' => __('Other', 'flexible-invoices')
			);

			foreach ( $invoices as $invoice ) {
				$_payment_method = get_post_meta( $invoice->ID, '_payment_method', true );
				$_payment_method_name = get_post_meta( $invoice->ID, '_payment_method_name', true );
				if ( $_payment_method_name == '' ) {
					$_wc_order_id = get_post_meta( $invoice->ID, '_wc_order_id', true );
					if ( $_wc_order_id != '' ) {
						$_payment_method_name = get_post_meta( $_wc_order_id, '_payment_method_title', true );
					}
					else {
						$_payment_method_name = $payment_methods[$_payment_method];
					}
					update_post_meta( $invoice->ID, '_payment_method_name', $_payment_method_name );
				}
			}

			update_option( 'inspire_invoices_payment_name_updated', true );
		}

		if ( !get_option( 'inspire_invoices_currency_updated' ) ) {
			update_option( 'inspire_invoices_currency', unserialize( 'a:3:{i:2;a:5:{s:8:"currency";s:3:"PLN";s:17:"currency_position";s:11:"right_space";s:18:"thousand_separator";s:1:" ";s:17:"decimal_separator";s:1:",";s:12:"num_decimals";s:1:"2";}i:1;a:5:{s:8:"currency";s:3:"USD";s:17:"currency_position";s:4:"left";s:18:"thousand_separator";s:1:",";s:17:"decimal_separator";s:1:".";s:12:"num_decimals";s:1:"2";}i:3;a:5:{s:8:"currency";s:3:"EUR";s:17:"currency_position";s:4:"left";s:18:"thousand_separator";s:1:",";s:17:"decimal_separator";s:1:".";s:12:"num_decimals";s:1:"2";}}' ) );
			update_option( 'inspire_invoices_currency_updated', true );
		}

		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			if ( !get_option( 'inspire_invoices_currency_woo_updated' ) ) {
				$inspire_invoices_currency = get_option( 'inspire_invoices_currency', array() );
				$woo_currency = get_option( 'woocommerce_currency', '' );
				if ( $woo_currency != '' ) {
					$add_currency = true;
					foreach ( $inspire_invoices_currency as $inspire_currency ) {
						if ( $inspire_currency['currency'] == $woo_currency ) {
							$add_currency = false;
						}
					}
					if ( $add_currency ) {
						$inspire_invoices_currency[] = array(
							'currency' 				=> $woo_currency,
							'currency_position' 	=> get_option( 'woocommerce_currency_pos', 'left' ),
							'thousand_separator'	=> get_option( 'woocommerce_price_thousand_sep', 'left' ),
							'decimal_separator'		=> get_option( 'woocommerce_price_decimal_sep', 'left' ),
							'num_decimals'			=> get_option( 'woocommerce_price_num_decimals', 'left' ),
						);
						update_option( 'inspire_invoices_currency', $inspire_invoices_currency );
					}
				}
				update_option( 'inspire_invoices_currency_woo_updated', true );
			}
		}

		if ( !get_option( 'inspire_invoices_tax_updated' ) ) {
			update_option( 'inspire_invoices_tax',  array(
				array( 'rate' => 23, 	'name' =>   '23%' ),
				array( 'rate' => 22, 	'name' =>   '22%' ),
				array( 'rate' => 21, 	'name' =>   '21%' ),
				array( 'rate' => 8, 	'name' =>    '8%' ),
				array( 'rate' => 7, 	'name' =>    '7%' ),
				array( 'rate' => 5, 	'name' =>    '5%' ),
				array( 'rate' => 3, 	'name' =>    '3%' ),
				array( 'rate' => 0, 	'name' =>    '0%' ),
				array( 'rate' => '0', 	'name' =>   'zw.' ),
				array( 'rate' => '0', 	'name' =>   'np.' ),
			));
			update_option( 'inspire_invoices_tax_updated', true );
		}
	}

	/**
	 * wordpress action
	 *
	 * inits css
	 */
	public function initAdminCssAction( $hook ) {
		$current_screen = get_current_screen();

	}



	public function getPluginNamespace() {
		return self::PLUGIN_NAMESPACE;
	}

	protected function _initBaseVariables()
	{
		$this->_pluginPath = $this->_plugin->get_plugin_dir();
		$this->_pluginUrl = $this->_plugin->get_plugin_url();
		$this->_pluginFileName = $this->_plugin->get_plugin_file_name();
		$this->_templatePath = '/' . $this->_pluginNamespace . '_templates';
		$this->_defaultViewArgs = array(
			'pluginUrl' => $this->_pluginUrl
		);
	}

	/**
	 * action_links function.
	 *
	 * @access public
	 * @param mixed $links
	 * @return void
	 */

	public static function getInstance() {
		if ( self::$_oInstance == false ) {
			self::$_oInstance = new Invoice();
		}
		return self::$_oInstance;
	}

	public function getTextDomain()
	{
		return $this->_textDomain;
	}

	public function getPluginUrl()
	{
		return $this->_pluginUrl;
	}

	public function get_plugin_dir()
	{
		return $this->_pluginPath;
	}

	public function getPluginFileName()
	{
		return $this->_pluginFileName;
	}

	public function getNamespace()
	{
		return $this->_pluginNamespace;
	}

	public function getSettingValue($name, $default = null)
	{
		return get_option($this->getNamespace() . '_' . $name, $default);
	}

	public function setSettingValue($name, $value)
	{
		return update_option($this->getNamespace() . '_' . $name, $value);
	}

	public function isSettingValue($name)
	{
		$option = get_option($this->getNamespace() . '_' . $name);
		return !empty($option);
	}

	public function getTemplatePath()
	{
		return $this->_templatePath;
	}

}




