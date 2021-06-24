<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class InvoicePost
{


	protected $_meta;

	protected $_id;
	protected $_plugin;
	protected $_post; // WP_Post

	protected $_number;
	protected $_formatted_number;

	protected $_date_issue; // created
	protected $_date_sale;
	protected $_date_pay;
	protected $_date_paid;

	protected $_products; // array(unit, quantity, net_price, discount, net_price_discount, net_price_sum, vat_rate, vat_sum, total_price)
	protected $_shipping = array();
	protected $_client; // array(wc_id, name, address, nip, bank, account, email, type = individual, company)
	protected $_client_filter_field; // pole w ktorym trzymamy wartość, na podstawie której możemy filtrować faktury po kliencie
	protected $_owner; // array(name, company_data, nip, bank, account)

	protected $_total_price;
	protected $_total_paid;
	protected $_discount;

	protected $_currency;

	protected $_currency_thousand_separator = '';
	protected $_currency_decimal_separator  = ',';
	protected $_currency_currency_position  = 'right_space';

	protected $_type; // default: invoice

	protected $_payment_status; // topay, paid
	protected $_payment_method;
	protected $_payment_method_name;

	protected $_notes;

	protected $_correction = '0';

	protected $_corrected_invoice_id;

	protected $wpml_user_lang;

	protected function _getMetaFields()
	{
		return array(
			'_number' => '_number',
			'_formatted_number' => '_formatted_number',
			'_date_issue' => '_date_issue',
			'_date_sale' => '_date_sale',
			'_date_pay' => '_date_pay',
			'_date_paid' => '_date_paid',
			'_products' => '_products',
			'_shipping' => '_shipping',
			'_client' => '_client',
			'_client_filter_field' => '_client_filter_field',
			'_owner' => '_owner',
			'_total_price' => '_total_price',
			'_total_paid' => '_total_paid',
			'_discount' => '_discount',
			'_currency' => '_currency',
			'_type' => '_type',
			'_payment_status' => '_payment_status',
			'_payment_method' => '_payment_method',
			'_payment_method_name' => '_payment_method_name',
			'_notes' => '_notes',
			'_correction' => '_correction',
			'_corrected_invoice_id' => '_corrected_invoice_id',
			'wpml_user_lang'        => 'wpml_user_lang'
		);
	}

	/**
	 * @return inspire_Plugin3
	 */
	public function getPlugin()
	{
		return $this->_plugin;
	}

	public function __construct($id, $plugin)
	{
		$this->_id = $id;
		$this->_plugin = $plugin;
		$this->_type = 'invoice';

		$this->refresh();
	}

	/**
	 * @return invoicePostType
	 */
	public function getPostType()
	{
		return Invoice::getInstance()->invoicePostType;
	}

	public function refresh()
	{
		// refresh posta
		$this->_post = WP_Post::get_instance($this->_id);

		// refresh metadanych
/*		$metadata = get_post_meta( $this->_id );

		foreach ( $this->_getMetaFields() as $field_name => $meta_name )
		{
			if( isset( $metadata[ $meta_name ][0] ) ) {
				$this->$field_name = $metadata[ $meta_name ][0];
			}
		}*/

		foreach ($this->_getMetaFields() as $field_name => $meta_name)
		{
			$this->$field_name = get_post_meta($this->_id, $meta_name, true);
		}

		// refresh zamowienia
		if (!empty($this->_wc_order_id))
		{
			try {
				$this->_wc_order = new WC_Order( $this->_wc_order_id );
			}
			catch ( Exception $e ) {}
		}

		if ( ! empty( $this->_currency ) ) {
			$inspire_invoices_currency = get_option('inspire_invoices_currency', array() );
			if ( is_array( $inspire_invoices_currency ) ) {
				foreach ( $inspire_invoices_currency as $currency ) {
					if ( $currency['currency'] == $this->_currency ) {
						$this->_currency_thousand_separator = $currency['thousand_separator'];
						$this->_currency_decimal_separator = $currency['decimal_separator'];
						$this->_currency_currency_position = $currency['currency_position'];
						break;
					}
				}
			}
		}
	}

	public function save() {
		foreach ($this->_getMetaFields() as $field_name => $meta_name)
		{
			update_post_meta($this->_id, $meta_name, $this->$field_name);
		}
		if ( isset( $this->_wc_order_id ) ) {
			if ( $this->_correction == '0' ) {
				update_post_meta( $this->_wc_order_id, '_invoice_generated', $this->_id );
			}
		}
		$this->increaseNextInvoiceNumber();
		$this->increaseNextCorrectionNumber();
	}

	public function getClient()
	{
		return $this->_client;
	}

	public function getOwner()
	{
		return $this->_owner;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function getTotalPrice()
	{
		return $this->stringAsMoneyField($this->_total_price);
	}

	public function getTotalPaid()
	{
		return $this->stringAsMoneyField($this->_total_paid);
	}

	public function getPaymentStatus()
	{
		return $this->_payment_status;
	}

	public function getPaymentStatusString()
	{
		$statuses = $this->getPostType()->getPaymentStatuses();
		return $statuses[$this->_payment_status];
	}

	public function getPaymentMethod()
	{
		return $this->_payment_method;
	}

	public function getPaymentMethodName()
	{
		return $this->_payment_method_name;
	}

	public function getPaymentMethodString()
	{
		$methods = $this->getPostType()->getPaymentMethods();
		$methods_woo = $this->getPostType()->getPaymentMethodsWoo();

		if ( isset( $methods[$this->_payment_method] ) ) {
			return $methods[$this->_payment_method];
		}
		if ( isset( $methods_woo[$this->_payment_method] ) ) {
			return __( $methods_woo[$this->_payment_method], 'admin_texts_woocommerce_gateways' );
		}
		if ( isset( $this->_payment_method_name ) &&  $this->_payment_method_name != '' ) {
			return $this->_payment_method_name;
		}
		return $this->_payment_method;
	}

	public function getActions( $invoice )
	{
		$actions = '<a target="_blank" href="' . site_url() . '/wp-admin/admin-ajax.php?action=invoice-get-pdf-invoice&amp;id=' . $invoice->getId() . '&amp;hash=' . md5(NONCE_SALT . $invoice->getId()) . '" class="button tips dashicons view-invoice" title="' . __( 'View Invoice', 'flexible-invoices' ) . '">' . __( 'View Invoice', 'flexible-invoices' ) . '</a>';
		$actions .= '<a target="_blank" href="' . site_url() . '/wp-admin/admin-ajax.php?action=invoice-get-pdf-invoice&amp;id=' . $invoice->getId() . '&amp;hash=' . md5(NONCE_SALT . $invoice->getId()) . '&save_file=1" class="button tips dashicons get-invoice" title="' . __( 'Download Invoice', 'flexible-invoices' ) . '">' . __( 'Download Invoice', 'flexible-invoices' ) . '</a>';
		return $actions;
	}

	/**
	 *
	 * @return array
	 */
	public function getProducts()
	{
		return $this->_products;
	}


	/**
	 *
	 * @return array
	 */
	public function getShipping()
	{
		return $this->_shipping;
	}

	/**
	 *
	 * @return number
	 */
	public function getProductsCount()
	{
		return count($this->_products);
	}

	/**
	 * @return int
	 */
	public function getNumber()
	{
		return $this->_number;
	}

	/**
	 * @return int
	 */
	public function getDateOfIssue()
	{
		if (!empty($this->_date_issue))
		{
			return date('Y-m-d', $this->_date_issue);
		} else {
			return null;
		}
	}

	/**
	 * @return int
	 */
	public function getDateOfPay()
	{
		if (!empty($this->_date_pay))
		{
			return date('Y-m-d', $this->_date_pay);
		} else {
			return null;
		}
	}

	/**
	 *
	 *
	 * @return
	 */
	public function getCurrencySymbol() {
		global $woocommerce;
		if (!empty( $woocommerce ) && function_exists( 'get_woocommerce_currency_symbol' ) ) {
			return get_woocommerce_currency_symbol( $this->getCurrency() );
		}
		else {
			return get_flexible_invoices_currency_symbol( $this->getCurrency() );
		}
	}

	public function stringAsMoney( $value )
	{

		if( is_string( $value ) ) {
			$value = (float) str_replace( ',', '.', $value );
		}

		$sign = '';
		if ( floatval($value) < 0 ) {
			$sign = '-';
		}
		$ret = @number_format( abs( floatval($value) ), 2, $this->_currency_decimal_separator, $this->_currency_thousand_separator );
		switch ( $this->_currency_currency_position ) {
			case 'left' :
				$ret = $this->getCurrencySymbol() . $ret;
				break;
			case 'right' :
				$ret .= $this->getCurrencySymbol();
				break;
			case 'left_space' :
				$ret = $this->getCurrencySymbol() . ' ' . $ret;
				break;
			case 'right_space' :
				$ret .= ' ' . $this->getCurrencySymbol();
				break;
		}
		$ret = $sign . $ret;
		return $ret;
		//return @number_format( floatval($value), 2, $this->_currency_decimal_separator, $this->_currency_thousand_separator) . ' ' . $this->getCurrencySymbol();
	}

	function stringAsMoneyField($value)
	{
		if ($value == 0)
		{
			$value = 0;
		}

		if( is_string( $value ) ) {
			$value = (float) str_replace( ',', '.', $value );
		}

		return number_format( $value, 2, '.', '' );
	}

	/**
	 * @return int
	 */
	public function getDateOfSale()
	{
		if ( empty( $this->_date_sale ) ) {
			return '';
		}
		return date('Y-m-d', $this->_date_sale);
	}


	public function getCurrency()
	{
		return $this->_currency;
	}

	public function getNumberOfDecimal() {

		$this->_currency;
	}

	public function getNotes()
	{
		return $this->_notes;
	}

	public function getWpmlUserLang()
	{
		return $this->wpml_user_lang;
	}

	/**
	 * @return string
	 */
	public function getFormattedInvoiceNumber()
	{
		//return $this->_formatted_number;
		if (!empty($this->_post))
		{
			return $this->_post->post_title;
		}
	}

	public function generateFormattedInvoiceNumber($number)
	{
		return self::generateFormattedInvoiceNumberForData($number, strtotime($this->getDateOfIssue()));
	}

	public static function generateFormattedInvoiceNumberForData($number, $dateOfIssue)
	{
//		$numberArray = array(
//			__(Invoice::getInstance()->getSettingValue('order_number_prefix', __( 'Invoice ', Invoice::getInstance()->getTextDomain() )), 'flexible-invoices'),
//			$number,
//			__(Invoice::getInstance()->getSettingValue('order_number_suffix', '/{MM}/{YYYY}'), 'flexible-invoices')
//		);

		$numberArray = array(

			Flexible_Invoices_Translator::translate_meta('inspire_invoices_order_number_prefix', __( 'Invoice ', Invoice::getInstance()->getTextDomain() )),
			$number,
			Flexible_Invoices_Translator::translate_meta('inspire_invoices_order_number_suffix', '/{MM}/{YYYY}')
		);


		foreach ($numberArray as &$value)
		{
			$value = str_replace(
				array('{DD}', '{MM}', '{YYYY}'),
				array(date('d', $dateOfIssue), date('m', $dateOfIssue), date('Y', $dateOfIssue)),
				$value
			);
		}

		return implode('', $numberArray);
	}

	public function generateFormattedCorrectionNumber($number)
	{
		return self::generateFormattedCorrectionNumberForData($number, strtotime($this->getDateOfIssue()));
	}

	public static function generateFormattedCorrectionNumberForData($number, $dateOfIssue)
	{
		$numberArray = array(
			__(Invoice::getInstance()->getSettingValue('correction_prefix', __( 'Corrected invoice ', Invoice::getInstance()->getTextDomain() )), 'flexible-invoices'),
			$number,
			__(Invoice::getInstance()->getSettingValue('correction_suffix', '/{MM}/{YYYY}'), 'flexible-invoices')
		);


		foreach ($numberArray as &$value)
		{
			$value = str_replace(
				array('{DD}', '{MM}', '{YYYY}'),
				array(date('d', $dateOfIssue), date('m', $dateOfIssue), date('Y', $dateOfIssue)),
				$value
			);
		}

		return implode('', $numberArray);
	}

	public function setOwnerFromDefault()
	{
		//name, company_data, nip, bank, account
		$this->_owner = array(
			'logo' => $this->_plugin->getSettingValue('company_logo'),
			'name' => $this->_plugin->getSettingValue('company_name'),
			'address' => $this->_plugin->getSettingValue('company_address'),
			'nip' =>  $this->_plugin->getSettingValue('company_nip'),
			'bank' =>  $this->_plugin->getSettingValue('bank_name'),
			'account' =>  $this->_plugin->getSettingValue('account_number'),
			'signature_user' =>  $this->_plugin->getSettingValue('signature_user')
		);
	}

	public function setNotesFromDefault()
	{
		$this->setNotes(__($this->getPlugin()->getSettingValue('invoices_notice'), 'flexible-invoices'));
	}

	public function setWpmlUserLangFromDefault(){
		$this->setWpmlUserLang( Flexible_Invoices_Translator::get_active_lang() );
	}

	/**
	 *
	 * @param int $number
	 */
	public function setNumber($number)
	{
		$this->_number = $number;
		if (!empty($this->_post))
		{
			$this->_post->post_title = $this->_formatted_number = $this->generateFormattedInvoiceNumber($number);
		}
	}

	/**
	 *
	 * @param int $number
	 */
	public function setCorrectionNumber($number)
	{
		$this->_number = $number;
		if (!empty($this->_post))
		{
			$this->_post->post_title = $this->_formatted_number = $this->generateFormattedCorrectionNumber($number);
		}
	}

	public function setDefaultPaymentStatus()
	{
		$defaultPayStatus = key($this->getPostType()->getPaymentStatuses());
		$this->setPaymentStatus($defaultPayStatus);
	}

	public function setDefaultValuesIfNumberEmpty() {
		if ( empty( $this->_number ) ) {
			$this->_date_sale = strtotime( current_time( 'mysql' ) );
			$this->_date_issue = strtotime( current_time( 'mysql' ) );
			$this->setNumber( $number = $this->_plugin->invoicePostType->generateNextInvoiceNumber( $this->getPlugin()->getSettingValue( 'number_reset_type', 'year' ), $this->_date_issue ) );
			$this->setOwnerFromDefault();
			$this->setNotesFromDefault();
			$this->setDefaultDatePay();

			$post = $this->getPost();
			if ( !empty( $post ) ) {
				$post->post_title = $this->generateFormattedInvoiceNumber( $number );
			}
		}
	}

	public function increaseNextInvoiceNumber() {
		$this->_plugin->invoicePostType->increaseNextInvoiceNumber( $this->getPlugin()->getSettingValue( 'number_reset_type', 'year' ), $this->_date_issue );
	}

	public function increaseNextCorrectionNumber() {
		$this->_plugin->invoicePostType->increaseNextCorrectionNumber( $this->getPlugin()->getSettingValue( 'number_reset_type', 'year' ), $this->_date_issue );
	}

	public function setDefaultDatePay()
	{
		//$this->_date_pay = strtotime($this->getDateOfSale()) + (60*60*24) * intval($this->getPlugin()->getSettingValue('pay_date_days'), 0);
		$this->_date_pay = strtotime($this->getDateOfIssue()) + (60*60*24) * intval($this->getPlugin()->getSettingValue('pay_date_days'), 0);
	}

	/**
	 *
	 */
	public function refreshTotals()
	{
		if (!empty($this->_products) || !empty($this->_shipping))
		{
			$this->_total_price = 0;

			if (!empty($this->_products))
			{
				foreach ($this->_products as $product)
				{
					$this->_total_price += (float) $product['total_price'];
				}
			}

			if (!empty($this->_shipping))
			{
				foreach ($this->_shipping as $product)
				{
					$this->_total_price += (float) $product['total_price'];
				}
			}
		} else {
			$this->_total_price = 0;
		}
		$this->_total_price = $this->_total_price;
	}

	public function getCalculatedTotalNetPrice()
	{
		if (!empty($this->_products) || !empty($this->_shipping))
		{
			$total_price = 0;

			if (!empty($this->_products))
			{
				foreach ($this->_products as $product)
				{
					$total_price += (float) $product['net_price_sum'];
				}
			}

			if (!empty($this->_shipping))
			{
				foreach ($this->_shipping as $product)
				{
					$total_price += (float) $product['net_price_sum'];
				}
			}
		} else {
			$total_price = 0;
		}
		return $total_price;
	}

	public function format_decimal( $number ) {
		$decimals = array( ',' );
		// Remove locale from string
		if ( ! is_float( $number ) ) {
			include 'wc-functions.php';
			$number = wc_clean( str_replace( $decimals, '.', $number ) );
		}
		return $number;

	}

	public function setProductsFromPostArray($postArray)
	{
		$this->_products = array();
		$this->_shipping = array();

		if (count($postArray) > 0)
		{
			foreach ($postArray['name'] as $index => $name)
			{
				$vatType = explode('|', $postArray['vat_type'][$index]);
				$this->_products[] = array(
					'name' => $name,

					'sku' => $postArray['sku'][$index],
					'unit' => $postArray['unit'][$index],
					'quantity' => $this->format_decimal( $postArray['quantity'][$index] ), // ilosc

					'net_price' => $this->format_decimal( $postArray['net_price'][$index] ), // bazowa kwota per 1 szt

					'discount' => 0, // obnizka

					'net_price_discount' => 0, // calkowita kwota bazowa, bez znizki
					'net_price_sum' => $this->format_decimal( $postArray['net_price_sum'][$index] ), // calkowita kwota, z wliczonymi zniżkami

					'vat_type' => $vatType[1], // stawka vat
					'vat_type_index' => $vatType[0], // identyfikator stawki
					'vat_type_name' => $vatType[2], // stawka vat

					'vat_rate' => $this->format_decimal( $postArray['vat_sum'][$index] ) / $this->format_decimal( $postArray['quantity'][$index] ), // vat per 1 szt
					'vat_sum' => $this->format_decimal( $postArray['vat_sum'][$index] ), // całkowity vat
					'total_price' => $this->format_decimal( $postArray['total_price'][$index] ), // kwota całkowita z podatkami i wszystkim

					/*'wc_item_type' => $item['type'],
					'wc_order_item_id' => $item['item_meta'],
					'wc_product_id' => $item['product_id'],
					'wc_variation_id' => $item['variation_id']*/
				);
			}
		}
	}

	public function getTotalTax()
	{
		$total_tax_amount = 0;

		$products = $this->getProducts();
		if (count($products) > 0 && is_array($products))
		{
			foreach ($products as $item)
			{
				$total_tax_amount += (float) $item['vat_sum'];
			}
		}
		return $total_tax_amount;
	}

	public function getTotalNet()
	{
		$total_net_price = 0;

		$products = $this->getProducts();
		if (count($products) > 0 && is_array($products))
		{
			foreach ($products as $item)
			{
				$total_net_price += (float) $item['net_price_sum'];
			}
		}
		return $total_net_price;
	}

	public function setOwnerFromArray($owner)
	{
		$this->_owner = array(
			'logo'    => $owner['logo'],
			'name'    => $owner['name'],
			'address' => $owner['address'],
			'nip'     => $owner['nip'],
			'bank'    => $owner['bank'],
			'account' => $owner['account'],
			'signature_user' => $owner['signature_user']
		);
	}

	public function setClientFromArray($client)
	{
		$this->_client = array(
			'name' => $client['name'],
			'street' => $client['street'],
			'postcode' => $client['postcode'],
			'city' => $client['city'],
			'nip' => $client['nip'],
			'country' => $client['country'],
			'phone' => $client['phone'],
			'email' => $client['email']
		);
		$this->_client_filter_field = $client['name'];
	}

	public function setFormattedNumber($value)
	{
		$this->_formatted_number = $value;
	}

	public function setDateOfSale($value)
	{
		$this->_date_sale = strtotime($value);
	}

	public function setDateOfIssue($value)
	{
		$this->_date_issue = strtotime($value);
	}

	public function setDateToPay($value)
	{
		$this->_date_pay = strtotime($value);
	}

	public function setTotalPrice($value)
	{
		$this->_total_price = $value;
	}

	public function setCurrency($value)
	{
		$this->_currency = $value;
	}

	public function setPaymentMethod($value)
	{
		$this->_payment_method = $value;
	}

	public function setPaymentMethodName($value)
	{
		$this->_payment_method_name = $value;
	}

	public function setPaymentStatus($value)
	{
		$this->_payment_status = $value;
	}

	public function setNotes($value)
	{
		$this->_notes = $value;
	}
	public function setWpmlUserLang($value)
	{
		$this->wpml_user_lang = $value;
	}

	public function setCorrection( $value ) {
		$this->_correction = $value;
	}

	public function getCorrection() {
		return $this->_correction;
	}

	public function setTotalPaid($value)
	{
		$this->_total_paid = $value;
	}

	public function getCorrectedInvoice() {
		if ( isset( $this->_corrected_invoice_id ) ) {
			$invoicePlugin = Invoice::getInstance();
			return $invoicePlugin->invoicePostType->invoiceFactory( $this->_corrected_invoice_id );
		}
		return false;
	}

	public function sendByEmail($email)
	{
		//
	}

	/**
	 * @param $invoice InvoicePost
	 */
	public function setCorrectionDataFromInvoice( $invoice ) {
		$meta_fields = $this->_getMetaFields();
		foreach ( $meta_fields as $field_name => $meta_name ) {
			$this->$field_name = $invoice->$field_name;
		}
		$this->_correction = '1';
		foreach ( $this->_products as $key => $product ) {
			$this->_products[$key]['quantity'] = -1 * $this->_products[$key]['quantity'];
			$this->_products[$key]['net_price_sum'] = -1 * $this->_products[$key]['net_price_sum'];
			$this->_products[$key]['vat_sum'] = -1 * $this->_products[$key]['vat_sum'];
			$this->_products[$key]['total_price'] = -1 * $this->_products[$key]['total_price'];
			$this->_products[$key]['before_correction'] = 1;
		}
		$this->_corrected_invoice_id = $invoice->getId();

		$this->_date_issue = strtotime( current_time( 'mysql' ) );

		$this->_date_pay = strtotime( current_time( 'mysql' ) ) + (60*60*24) * intval($this->getPlugin()->getSettingValue('correction_default_due_time'), 0);

	}

	public function get($name)
	{
		if (isset($this->_post->$name))
		{
			return $this->_post->$name;
		} else {

			return $this->_meta[$name];
		}
	}

	public function setMeta($name, $value)
	{
		$this->_meta[$name] = $value;
		return update_post_meta($this->_post->ID, $name, $value);
	}

	/**
	 *
	 * @return WP_Post
	 */
	public function getPost()
	{
		return $this->_post;
	}

}
