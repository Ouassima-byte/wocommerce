<?php

register_deactivation_hook($plugin_file, 'flexible_invoices_deactivation');
function flexible_invoices_deactivation() {
	Flexible_Invoices_Translator::reset_translations();
}

//register_activation_hook($plugin_file, 'flexible_invoices_activation');
//function flexible_invoices_activation() {
//	Flexible_Invoices_Translator::install_translations();
//}

function get_flexible_invoices_currency_symbol( $currency ) {
	$symbols = apply_filters( 'flexible_invoices_currency_symbols', array(
		'AED' => '&#x62f;.&#x625;',
		'AFN' => '&#x60b;',
		'ALL' => 'L',
		'AMD' => 'AMD',
		'ANG' => '&fnof;',
		'AOA' => 'Kz',
		'ARS' => '&#36;',
		'AUD' => '&#36;',
		'AWG' => 'Afl.',
		'AZN' => 'AZN',
		'BAM' => 'KM',
		'BBD' => '&#36;',
		'BDT' => '&#2547;&nbsp;',
		'BGN' => '&#1083;&#1074;.',
		'BHD' => '.&#x62f;.&#x628;',
		'BIF' => 'Fr',
		'BMD' => '&#36;',
		'BND' => '&#36;',
		'BOB' => 'Bs.',
		'BRL' => '&#82;&#36;',
		'BSD' => '&#36;',
		'BTC' => '&#3647;',
		'BTN' => 'Nu.',
		'BWP' => 'P',
		'BYR' => 'Br',
		'BZD' => '&#36;',
		'CAD' => '&#36;',
		'CDF' => 'Fr',
		'CHF' => '&#67;&#72;&#70;',
		'CLP' => '&#36;',
		'CNY' => '&yen;',
		'COP' => '&#36;',
		'CRC' => '&#x20a1;',
		'CUC' => '&#36;',
		'CUP' => '&#36;',
		'CVE' => '&#36;',
		'CZK' => '&#75;&#269;',
		'DJF' => 'Fr',
		'DKK' => 'DKK',
		'DOP' => 'RD&#36;',
		'DZD' => '&#x62f;.&#x62c;',
		'EGP' => 'EGP',
		'ERN' => 'Nfk',
		'ETB' => 'Br',
		'EUR' => '&euro;',
		'FJD' => '&#36;',
		'FKP' => '&pound;',
		'GBP' => '&pound;',
		'GEL' => '&#x10da;',
		'GGP' => '&pound;',
		'GHS' => '&#x20b5;',
		'GIP' => '&pound;',
		'GMD' => 'D',
		'GNF' => 'Fr',
		'GTQ' => 'Q',
		'GYD' => '&#36;',
		'HKD' => '&#36;',
		'HNL' => 'L',
		'HRK' => 'Kn',
		'HTG' => 'G',
		'HUF' => '&#70;&#116;',
		'IDR' => 'Rp',
		'ILS' => '&#8362;',
		'IMP' => '&pound;',
		'INR' => '&#8377;',
		'IQD' => '&#x639;.&#x62f;',
		'IRR' => '&#xfdfc;',
		'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
		'ISK' => 'kr.',
		'JEP' => '&pound;',
		'JMD' => '&#36;',
		'JOD' => '&#x62f;.&#x627;',
		'JPY' => '&yen;',
		'KES' => 'KSh',
		'KGS' => '&#x441;&#x43e;&#x43c;',
		'KHR' => '&#x17db;',
		'KMF' => 'Fr',
		'KPW' => '&#x20a9;',
		'KRW' => '&#8361;',
		'KWD' => '&#x62f;.&#x643;',
		'KYD' => '&#36;',
		'KZT' => 'KZT',
		'LAK' => '&#8365;',
		'LBP' => '&#x644;.&#x644;',
		'LKR' => '&#xdbb;&#xdd4;',
		'LRD' => '&#36;',
		'LSL' => 'L',
		'LYD' => '&#x644;.&#x62f;',
		'MAD' => '&#x62f;.&#x645;.',
		'MDL' => 'MDL',
		'MGA' => 'Ar',
		'MKD' => '&#x434;&#x435;&#x43d;',
		'MMK' => 'Ks',
		'MNT' => '&#x20ae;',
		'MOP' => 'P',
		'MRO' => 'UM',
		'MUR' => '&#x20a8;',
		'MVR' => '.&#x783;',
		'MWK' => 'MK',
		'MXN' => '&#36;',
		'MYR' => '&#82;&#77;',
		'MZN' => 'MT',
		'NAD' => '&#36;',
		'NGN' => '&#8358;',
		'NIO' => 'C&#36;',
		'NOK' => '&#107;&#114;',
		'NPR' => '&#8360;',
		'NZD' => '&#36;',
		'OMR' => '&#x631;.&#x639;.',
		'PAB' => 'B/.',
		'PEN' => 'S/.',
		'PGK' => 'K',
		'PHP' => '&#8369;',
		'PKR' => '&#8360;',
		'PLN' => '&#122;&#322;',
		'PRB' => '&#x440;.',
		'PYG' => '&#8370;',
		'QAR' => '&#x631;.&#x642;',
		'RMB' => '&yen;',
		'RON' => 'lei',
		'RSD' => '&#x434;&#x438;&#x43d;.',
		'RUB' => '&#8381;',
		'RWF' => 'Fr',
		'SAR' => '&#x631;.&#x633;',
		'SBD' => '&#36;',
		'SCR' => '&#x20a8;',
		'SDG' => '&#x62c;.&#x633;.',
		'SEK' => '&#107;&#114;',
		'SGD' => '&#36;',
		'SHP' => '&pound;',
		'SLL' => 'Le',
		'SOS' => 'Sh',
		'SRD' => '&#36;',
		'SSP' => '&pound;',
		'STD' => 'Db',
		'SYP' => '&#x644;.&#x633;',
		'SZL' => 'L',
		'THB' => '&#3647;',
		'TJS' => '&#x405;&#x41c;',
		'TMT' => 'm',
		'TND' => '&#x62f;.&#x62a;',
		'TOP' => 'T&#36;',
		'TRY' => '&#8378;',
		'TTD' => '&#36;',
		'TWD' => '&#78;&#84;&#36;',
		'TZS' => 'Sh',
		'UAH' => '&#8372;',
		'UGX' => 'UGX',
		'USD' => '&#36;',
		'UYU' => '&#36;',
		'UZS' => 'UZS',
		'VEF' => 'Bs F',
		'VND' => '&#8363;',
		'VUV' => 'Vt',
		'WST' => 'T',
		'XAF' => 'Fr',
		'XCD' => '&#36;',
		'XOF' => 'Fr',
		'XPF' => 'Fr',
		'YER' => '&#xfdfc;',
		'ZAR' => '&#82;',
		'ZMW' => 'ZK',
	) );
	if ( isset( $symbols[$currency] ) ) {
		return $symbols[$currency];
	}
	else {
		return $currency;
	}
}

function get_flexible_invoices_currencies() {
	return array_unique(
			apply_filters( 'flexible_invoices_currencies',
					array(
							'AED' => __( 'United Arab Emirates dirham', 'flexible-invoices' ),
							'AFN' => __( 'Afghan afghani', 'flexible-invoices' ),
							'ALL' => __( 'Albanian lek', 'flexible-invoices' ),
							'AMD' => __( 'Armenian dram', 'flexible-invoices' ),
							'ANG' => __( 'Netherlands Antillean guilder', 'flexible-invoices' ),
							'AOA' => __( 'Angolan kwanza', 'flexible-invoices' ),
							'ARS' => __( 'Argentine peso', 'flexible-invoices' ),
							'AUD' => __( 'Australian dollar', 'flexible-invoices' ),
							'AWG' => __( 'Aruban florin', 'flexible-invoices' ),
							'AZN' => __( 'Azerbaijani manat', 'flexible-invoices' ),
							'BAM' => __( 'Bosnia and Herzegovina convertible mark', 'flexible-invoices' ),
							'BBD' => __( 'Barbadian dollar', 'flexible-invoices' ),
							'BDT' => __( 'Bangladeshi taka', 'flexible-invoices' ),
							'BGN' => __( 'Bulgarian lev', 'flexible-invoices' ),
							'BHD' => __( 'Bahraini dinar', 'flexible-invoices' ),
							'BIF' => __( 'Burundian franc', 'flexible-invoices' ),
							'BMD' => __( 'Bermudian dollar', 'flexible-invoices' ),
							'BND' => __( 'Brunei dollar', 'flexible-invoices' ),
							'BOB' => __( 'Bolivian boliviano', 'flexible-invoices' ),
							'BRL' => __( 'Brazilian real', 'flexible-invoices' ),
							'BSD' => __( 'Bahamian dollar', 'flexible-invoices' ),
							'BTC' => __( 'Bitcoin', 'flexible-invoices' ),
							'BTN' => __( 'Bhutanese ngultrum', 'flexible-invoices' ),
							'BWP' => __( 'Botswana pula', 'flexible-invoices' ),
							'BYR' => __( 'Belarusian ruble', 'flexible-invoices' ),
							'BZD' => __( 'Belize dollar', 'flexible-invoices' ),
							'CAD' => __( 'Canadian dollar', 'flexible-invoices' ),
							'CDF' => __( 'Congolese franc', 'flexible-invoices' ),
							'CHF' => __( 'Swiss franc', 'flexible-invoices' ),
							'CLP' => __( 'Chilean peso', 'flexible-invoices' ),
							'CNY' => __( 'Chinese yuan', 'flexible-invoices' ),
							'COP' => __( 'Colombian peso', 'flexible-invoices' ),
							'CRC' => __( 'Costa Rican col&oacute;n', 'flexible-invoices' ),
							'CUC' => __( 'Cuban convertible peso', 'flexible-invoices' ),
							'CUP' => __( 'Cuban peso', 'flexible-invoices' ),
							'CVE' => __( 'Cape Verdean escudo', 'flexible-invoices' ),
							'CZK' => __( 'Czech koruna', 'flexible-invoices' ),
							'DJF' => __( 'Djiboutian franc', 'flexible-invoices' ),
							'DKK' => __( 'Danish krone', 'flexible-invoices' ),
							'DOP' => __( 'Dominican peso', 'flexible-invoices' ),
							'DZD' => __( 'Algerian dinar', 'flexible-invoices' ),
							'EGP' => __( 'Egyptian pound', 'flexible-invoices' ),
							'ERN' => __( 'Eritrean nakfa', 'flexible-invoices' ),
							'ETB' => __( 'Ethiopian birr', 'flexible-invoices' ),
							'EUR' => __( 'Euro', 'flexible-invoices' ),
							'FJD' => __( 'Fijian dollar', 'flexible-invoices' ),
							'FKP' => __( 'Falkland Islands pound', 'flexible-invoices' ),
							'GBP' => __( 'Pound sterling', 'flexible-invoices' ),
							'GEL' => __( 'Georgian lari', 'flexible-invoices' ),
							'GGP' => __( 'Guernsey pound', 'flexible-invoices' ),
							'GHS' => __( 'Ghana cedi', 'flexible-invoices' ),
							'GIP' => __( 'Gibraltar pound', 'flexible-invoices' ),
							'GMD' => __( 'Gambian dalasi', 'flexible-invoices' ),
							'GNF' => __( 'Guinean franc', 'flexible-invoices' ),
							'GTQ' => __( 'Guatemalan quetzal', 'flexible-invoices' ),
							'GYD' => __( 'Guyanese dollar', 'flexible-invoices' ),
							'HKD' => __( 'Hong Kong dollar', 'flexible-invoices' ),
							'HNL' => __( 'Honduran lempira', 'flexible-invoices' ),
							'HRK' => __( 'Croatian kuna', 'flexible-invoices' ),
							'HTG' => __( 'Haitian gourde', 'flexible-invoices' ),
							'HUF' => __( 'Hungarian forint', 'flexible-invoices' ),
							'IDR' => __( 'Indonesian rupiah', 'flexible-invoices' ),
							'ILS' => __( 'Israeli new shekel', 'flexible-invoices' ),
							'IMP' => __( 'Manx pound', 'flexible-invoices' ),
							'INR' => __( 'Indian rupee', 'flexible-invoices' ),
							'IQD' => __( 'Iraqi dinar', 'flexible-invoices' ),
							'IRR' => __( 'Iranian rial', 'flexible-invoices' ),
							'ISK' => __( 'Icelandic kr&oacute;na', 'flexible-invoices' ),
							'JEP' => __( 'Jersey pound', 'flexible-invoices' ),
							'JMD' => __( 'Jamaican dollar', 'flexible-invoices' ),
							'JOD' => __( 'Jordanian dinar', 'flexible-invoices' ),
							'JPY' => __( 'Japanese yen', 'flexible-invoices' ),
							'KES' => __( 'Kenyan shilling', 'flexible-invoices' ),
							'KGS' => __( 'Kyrgyzstani som', 'flexible-invoices' ),
							'KHR' => __( 'Cambodian riel', 'flexible-invoices' ),
							'KMF' => __( 'Comorian franc', 'flexible-invoices' ),
							'KPW' => __( 'North Korean won', 'flexible-invoices' ),
							'KRW' => __( 'South Korean won', 'flexible-invoices' ),
							'KWD' => __( 'Kuwaiti dinar', 'flexible-invoices' ),
							'KYD' => __( 'Cayman Islands dollar', 'flexible-invoices' ),
							'KZT' => __( 'Kazakhstani tenge', 'flexible-invoices' ),
							'LAK' => __( 'Lao kip', 'flexible-invoices' ),
							'LBP' => __( 'Lebanese pound', 'flexible-invoices' ),
							'LKR' => __( 'Sri Lankan rupee', 'flexible-invoices' ),
							'LRD' => __( 'Liberian dollar', 'flexible-invoices' ),
							'LSL' => __( 'Lesotho loti', 'flexible-invoices' ),
							'LYD' => __( 'Libyan dinar', 'flexible-invoices' ),
							'MAD' => __( 'Moroccan dirham', 'flexible-invoices' ),
							'MDL' => __( 'Moldovan leu', 'flexible-invoices' ),
							'MGA' => __( 'Malagasy ariary', 'flexible-invoices' ),
							'MKD' => __( 'Macedonian denar', 'flexible-invoices' ),
							'MMK' => __( 'Burmese kyat', 'flexible-invoices' ),
							'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', 'flexible-invoices' ),
							'MOP' => __( 'Macanese pataca', 'flexible-invoices' ),
							'MRO' => __( 'Mauritanian ouguiya', 'flexible-invoices' ),
							'MUR' => __( 'Mauritian rupee', 'flexible-invoices' ),
							'MVR' => __( 'Maldivian rufiyaa', 'flexible-invoices' ),
							'MWK' => __( 'Malawian kwacha', 'flexible-invoices' ),
							'MXN' => __( 'Mexican peso', 'flexible-invoices' ),
							'MYR' => __( 'Malaysian ringgit', 'flexible-invoices' ),
							'MZN' => __( 'Mozambican metical', 'flexible-invoices' ),
							'NAD' => __( 'Namibian dollar', 'flexible-invoices' ),
							'NGN' => __( 'Nigerian naira', 'flexible-invoices' ),
							'NIO' => __( 'Nicaraguan c&oacute;rdoba', 'flexible-invoices' ),
							'NOK' => __( 'Norwegian krone', 'flexible-invoices' ),
							'NPR' => __( 'Nepalese rupee', 'flexible-invoices' ),
							'NZD' => __( 'New Zealand dollar', 'flexible-invoices' ),
							'OMR' => __( 'Omani rial', 'flexible-invoices' ),
							'PAB' => __( 'Panamanian balboa', 'flexible-invoices' ),
							'PEN' => __( 'Peruvian nuevo sol', 'flexible-invoices' ),
							'PGK' => __( 'Papua New Guinean kina', 'flexible-invoices' ),
							'PHP' => __( 'Philippine peso', 'flexible-invoices' ),
							'PKR' => __( 'Pakistani rupee', 'flexible-invoices' ),
							'PLN' => __( 'Polish z&#x142;oty', 'flexible-invoices' ),
							'PRB' => __( 'Transnistrian ruble', 'flexible-invoices' ),
							'PYG' => __( 'Paraguayan guaran&iacute;', 'flexible-invoices' ),
							'QAR' => __( 'Qatari riyal', 'flexible-invoices' ),
							'RON' => __( 'Romanian leu', 'flexible-invoices' ),
							'RSD' => __( 'Serbian dinar', 'flexible-invoices' ),
							'RUB' => __( 'Russian ruble', 'flexible-invoices' ),
							'RWF' => __( 'Rwandan franc', 'flexible-invoices' ),
							'SAR' => __( 'Saudi riyal', 'flexible-invoices' ),
							'SBD' => __( 'Solomon Islands dollar', 'flexible-invoices' ),
							'SCR' => __( 'Seychellois rupee', 'flexible-invoices' ),
							'SDG' => __( 'Sudanese pound', 'flexible-invoices' ),
							'SEK' => __( 'Swedish krona', 'flexible-invoices' ),
							'SGD' => __( 'Singapore dollar', 'flexible-invoices' ),
							'SHP' => __( 'Saint Helena pound', 'flexible-invoices' ),
							'SLL' => __( 'Sierra Leonean leone', 'flexible-invoices' ),
							'SOS' => __( 'Somali shilling', 'flexible-invoices' ),
							'SRD' => __( 'Surinamese dollar', 'flexible-invoices' ),
							'SSP' => __( 'South Sudanese pound', 'flexible-invoices' ),
							'STD' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'flexible-invoices' ),
							'SYP' => __( 'Syrian pound', 'flexible-invoices' ),
							'SZL' => __( 'Swazi lilangeni', 'flexible-invoices' ),
							'THB' => __( 'Thai baht', 'flexible-invoices' ),
							'TJS' => __( 'Tajikistani somoni', 'flexible-invoices' ),
							'TMT' => __( 'Turkmenistan manat', 'flexible-invoices' ),
							'TND' => __( 'Tunisian dinar', 'flexible-invoices' ),
							'TOP' => __( 'Tongan pa&#x2bb;anga', 'flexible-invoices' ),
							'TRY' => __( 'Turkish lira', 'flexible-invoices' ),
							'TTD' => __( 'Trinidad and Tobago dollar', 'flexible-invoices' ),
							'TWD' => __( 'New Taiwan dollar', 'flexible-invoices' ),
							'TZS' => __( 'Tanzanian shilling', 'flexible-invoices' ),
							'UAH' => __( 'Ukrainian hryvnia', 'flexible-invoices' ),
							'UGX' => __( 'Ugandan shilling', 'flexible-invoices' ),
							'USD' => __( 'United States dollar', 'flexible-invoices' ),
							'UYU' => __( 'Uruguayan peso', 'flexible-invoices' ),
							'UZS' => __( 'Uzbekistani som', 'flexible-invoices' ),
							'VEF' => __( 'Venezuelan bol&iacute;var', 'flexible-invoices' ),
							'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', 'flexible-invoices' ),
							'VUV' => __( 'Vanuatu vatu', 'flexible-invoices' ),
							'WST' => __( 'Samoan t&#x101;l&#x101;', 'flexible-invoices' ),
							'XAF' => __( 'Central African CFA franc', 'flexible-invoices' ),
							'XCD' => __( 'East Caribbean dollar', 'flexible-invoices' ),
							'XOF' => __( 'West African CFA franc', 'flexible-invoices' ),
							'XPF' => __( 'CFP franc', 'flexible-invoices' ),
							'YER' => __( 'Yemeni rial', 'flexible-invoices' ),
							'ZAR' => __( 'South African rand', 'flexible-invoices' ),
							'ZMW' => __( 'Zambian kwacha', 'flexible-invoices' ),
					)
			)
	);
}

function get_flexible_invoices_currency_position( $symbol = null ) {
	if ( empty( $symbol ) ) {
		$symbol = __( 'USD', 'woocommerce' );
	}
	return array(
		'left'        => __( 'Left', 'woocommerce' ) . ' (' . $symbol . '99.99)',
		'right'       => __( 'Right', 'woocommerce' ) . ' (99.99' . $symbol . ')',
		'left_space'  => __( 'Left with space', 'woocommerce' ) . ' (' . $symbol . ' 99.99)',
		'right_space' => __( 'Right with space', 'woocommerce' ) . ' (99.99 ' . $symbol . ')'
	);
}


function get_flexible_invoices_locale_info( $symbol = null ) {
	return array(
		'AU' => array(
				'currency_code'  => 'AUD',
				'currency_pos'   => 'left',
				'thousand_sep'   => ',',
				'decimal_sep'    => '.',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'AU',
										'state'    => '',
										'rate'     => '10.0000',
										'name'     => 'GST',
										'shipping' => true
								)
						)
				)
		),
		'BD' => array(
				'currency_code'  => 'BDT',
				'currency_pos'   => 'left',
				'thousand_sep'   => ',',
				'decimal_sep'    => '.',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'in',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'BD',
										'state'    => '',
										'rate'     => '15.0000',
										'name'     => 'VAT',
										'shipping' => true
								)
						)
				)
		),
		'BE' => array(
				'currency_code'  => 'EUR',
				'currency_pos'   => 'left',
				'thousand_sep'   => ' ',
				'decimal_sep'    => ',',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'BE',
										'state'    => '',
										'rate'     => '20.0000',
										'name'     => 'BTW',
										'shipping' => true
								)
						)
				)
		),
		'BR' => array(
				'currency_code'  => 'BRL',
				'currency_pos'   => 'left',
				'thousand_sep'   => '.',
				'decimal_sep'    => ',',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array()
		),
		'CA' => array(
				'currency_code'  => 'CAD',
				'currency_pos'   => 'left',
				'thousand_sep'   => ',',
				'decimal_sep'    => '.',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'BC' => array(
								array(
										'country'  => 'CA',
										'state'    => 'BC',
										'rate'     => '7.0000',
										'name'     => _x( 'PST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => false,
										'priority' => 2
								)
						),
						'SK' => array(
								array(
										'country'  => 'CA',
										'state'    => 'SK',
										'rate'     => '5.0000',
										'name'     => _x( 'PST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => false,
										'priority' => 2
								)
						),
						'MB' => array(
								array(
										'country'  => 'CA',
										'state'    => 'MB',
										'rate'     => '8.0000',
										'name'     => _x( 'PST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => false,
										'priority' => 2
								)
						),
						'QC' => array(
								array(
										'country'  => 'CA',
										'state'    => 'QC',
										'rate'     => '9.975',
										'name'     => _x( 'QST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => false,
										'priority' => 2
								)
						),
						'*' => array(
								array(
										'country'  => 'CA',
										'state'    => 'ON',
										'rate'     => '13.0000',
										'name'     => _x( 'HST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => true
								),
								array(
										'country'  => 'CA',
										'state'    => 'NL',
										'rate'     => '13.0000',
										'name'     => _x( 'HST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => true
								),
								array(
										'country'  => 'CA',
										'state'    => 'NB',
										'rate'     => '13.0000',
										'name'     => _x( 'HST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => true
								),
								array(
										'country'  => 'CA',
										'state'    => 'PE',
										'rate'     => '14.0000',
										'name'     => _x( 'HST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => true
								),
								array(
										'country'  => 'CA',
										'state'    => 'NS',
										'rate'     => '15.0000',
										'name'     => _x( 'HST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => true
								),
								array(
										'country'  => 'CA',
										'state'    => 'AB',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => true
								),
								array(
										'country'  => 'CA',
										'state'    => 'BC',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => true
								),
								array(
										'country'  => 'CA',
										'state'    => 'NT',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => true
								),
								array(
										'country'  => 'CA',
										'state'    => 'NU',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => true
								),
								array(
										'country'  => 'CA',
										'state'    => 'YT',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => true
								),
								array(
										'country'  => 'CA',
										'state'    => 'SK',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => true
								),
								array(
										'country'  => 'CA',
										'state'    => 'MB',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => true
								),
								array(
										'country'  => 'CA',
										'state'    => 'QC',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'woocommerce' ),
										'shipping' => true
								)
						)
				)
		),
		'DE' => array(
				'currency_code'  => 'EUR',
				'currency_pos'   => 'left',
				'thousand_sep'   => '.',
				'decimal_sep'    => ',',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'DE',
										'state'    => '',
										'rate'     => '19.0000',
										'name'     => 'Mwst.',
										'shipping' => true
								)
						)
				)
		),
		'ES' => array(
				'currency_code'  => 'EUR',
				'currency_pos'   => 'right',
				'thousand_sep'   => '.',
				'decimal_sep'    => ',',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'ES',
										'state'    => '',
										'rate'     => '21.0000',
										'name'     => 'VAT',
										'shipping' => true
								)
						)
				)
		),
		'FR' => array(
				'currency_code'  => 'EUR',
				'currency_pos'   => 'right',
				'thousand_sep'   => ' ',
				'decimal_sep'    => ',',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'FR',
										'state'    => '',
										'rate'     => '20.0000',
										'name'     => 'VAT',
										'shipping' => true
								)
						)
				)
		),
		'GB' => array(
				'currency_code'  => 'GBP',
				'currency_pos'	=> 'left',
				'thousand_sep'	=> ',',
				'decimal_sep'	 => '.',
				'num_decimals'	=> 2,
				'weight_unit'	 => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'		=> array(
						'' => array(
								array(
										'country'  => 'GB',
										'state'	 => '',
										'rate'	  => '20.0000',
										'name'	  => 'VAT',
										'shipping' => true
								)
						)
				)
		),
		'HU' => array(
				'currency_code'  => 'HUF',
				'currency_pos'   => 'right_space',
				'thousand_sep'   => ' ',
				'decimal_sep'    => ',',
				'num_decimals'   => 0,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'HU',
										'state'    => '',
										'rate'     => '27.0000',
										'name'     => 'ÁFA',
										'shipping' => true
								)
						)
				)
		),
		'IT' => array(
				'currency_code'  => 'EUR',
				'currency_pos'   => 'right',
				'thousand_sep'   => '.',
				'decimal_sep'    => ',',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'IT',
										'state'    => '',
										'rate'     => '22.0000',
										'name'     => 'IVA',
										'shipping' => true
								)
						)
				)
		),
		'JP' => array(
				'currency_code'  => 'JPY',
				'currency_pos'   => 'left',
				'thousand_sep'   => ',',
				'decimal_sep'    => '.',
				'num_decimals'   => 0,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'JP',
										'state'    => '',
										'rate'     => '8.0000',
										'name'     => __( 'Consumption tax', 'woocommerce' ),
										'shipping' => true
								)
						)
				)
		),
		'NL' => array(
				'currency_code'  => 'EUR',
				'currency_pos'   => 'left',
				'thousand_sep'   => ',',
				'decimal_sep'    => '.',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'NL',
										'state'    => '',
										'rate'     => '21.0000',
										'name'     => 'VAT',
										'shipping' => true
								)
						)
				)
		),
		'NO' => array(
				'currency_code'  => 'Kr',
				'currency_pos'   => 'left_space',
				'thousand_sep'   => '.',
				'decimal_sep'    => ',',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'NO',
										'state'    => '',
										'rate'     => '25.0000',
										'name'     => 'MVA',
										'shipping' => true
								)
						)
				)
		),
		'NP' => array(
				'currency_code'  => 'NPR',
				'currency_pos'   => 'left_space',
				'thousand_sep'   => ',',
				'decimal_sep'    => '.',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'NP',
										'state'    => '',
										'rate'     => '13.0000',
										'name'     => 'VAT',
										'shipping' => true
								)
						)
				)
		),
		'PL' => array(
				'currency_code'  => 'PLN',
				'currency_pos'   => 'right',
				'thousand_sep'   => ' ',
				'decimal_sep'    => ',',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'PL',
										'state'    => '',
										'rate'     => '23.0000',
										'name'     => 'VAT',
										'shipping' => true
								)
						)
				)
		),
		'TH' => array(
				'currency_code'  => 'THB',
				'currency_pos'   => 'left',
				'thousand_sep'   => ',',
				'decimal_sep'    => '.',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'TH',
										'state'    => '',
										'rate'     => '7.0000',
										'name'     => 'VAT',
										'shipping' => true
								)
						)
				)
		),
		'TR' => array(
				'currency_code'  => 'TRY',
				'currency_pos'   => 'left_space',
				'thousand_sep'   => '.',
				'decimal_sep'    => ',',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'TR',
										'state'    => '',
										'rate'     => '18.0000',
										'name'     => 'KDV',
										'shipping' => true
								)
						)
				)
		),
		'US' => array(
				'currency_code'  => 'USD',
				'currency_pos'	=> 'left',
				'thousand_sep'	=> ',',
				'decimal_sep'	 => '.',
				'num_decimals'	=> 2,
				'weight_unit'	 => 'lbs',
				'dimension_unit' => 'in',
				'tax_rates'		=> array(
						'AL' => array(
								array(
										'country'  => 'US',
										'state'    => 'AL',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'AZ' => array(
								array(
										'country'  => 'US',
										'state'    => 'AZ',
										'rate'     => '5.6000',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'AR' => array(
								array(
										'country'  => 'US',
										'state'    => 'AR',
										'rate'     => '6.5000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'CA' => array(
								array(
										'country'  => 'US',
										'state'    => 'CA',
										'rate'     => '7.5000',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'CO' => array(
								array(
										'country'  => 'US',
										'state'    => 'CO',
										'rate'     => '2.9000',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'CT' => array(
								array(
										'country'  => 'US',
										'state'    => 'CT',
										'rate'     => '6.3500',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'DC' => array(
								array(
										'country'  => 'US',
										'state'    => 'DC',
										'rate'     => '5.7500',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'FL' => array(
								array(
										'country'  => 'US',
										'state'    => 'FL',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'GA' => array(
								array(
										'country'  => 'US',
										'state'    => 'GA',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'GU' => array(
								array(
										'country'  => 'US',
										'state'    => 'GU',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'HI' => array(
								array(
										'country'  => 'US',
										'state'    => 'HI',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'ID' => array(
								array(
										'country'  => 'US',
										'state'    => 'ID',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'IL' => array(
								array(
										'country'  => 'US',
										'state'    => 'IL',
										'rate'     => '6.2500',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'IN' => array(
								array(
										'country'  => 'US',
										'state'    => 'IN',
										'rate'     => '7.0000',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'IA' => array(
								array(
										'country'  => 'US',
										'state'    => 'IA',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'KS' => array(
								array(
										'country'  => 'US',
										'state'    => 'KS',
										'rate'     => '6.1500',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'KY' => array(
								array(
										'country'  => 'US',
										'state'    => 'KY',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'LA' => array(
								array(
										'country'  => 'US',
										'state'    => 'LA',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'ME' => array(
								array(
										'country'  => 'US',
										'state'    => 'ME',
										'rate'     => '5.5000',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'MD' => array(
								array(
										'country'  => 'US',
										'state'    => 'MD',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'MA' => array(
								array(
										'country'  => 'US',
										'state'    => 'MA',
										'rate'     => '6.2500',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'MI' => array(
								array(
										'country'  => 'US',
										'state'    => 'MI',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'MN' => array(
								array(
										'country'  => 'US',
										'state'    => 'MN',
										'rate'     => '6.8750',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'MS' => array(
								array(
										'country'  => 'US',
										'state'    => 'MS',
										'rate'     => '7.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'MO' => array(
								array(
										'country'  => 'US',
										'state'    => 'MO',
										'rate'     => '4.225',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'NE' => array(
								array(
										'country'  => 'US',
										'state'    => 'NE',
										'rate'     => '5.5000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'NV' => array(
								array(
										'country'  => 'US',
										'state'    => 'NV',
										'rate'     => '6.8500',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'NJ' => array(
								array(
										'country'  => 'US',
										'state'    => 'NJ',
										'rate'     => '7.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'NM' => array(
								array(
										'country'  => 'US',
										'state'    => 'NM',
										'rate'     => '5.1250',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'NY' => array(
								array(
										'country'  => 'US',
										'state'    => 'NY',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'NC' => array(
								array(
										'country'  => 'US',
										'state'    => 'NC',
										'rate'     => '4.7500',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'ND' => array(
								array(
										'country'  => 'US',
										'state'    => 'ND',
										'rate'     => '5.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'OH' => array(
								array(
										'country'  => 'US',
										'state'    => 'OH',
										'rate'     => '5.7500',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'OK' => array(
								array(
										'country'  => 'US',
										'state'    => 'OK',
										'rate'     => '4.5000',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'PA' => array(
								array(
										'country'  => 'US',
										'state'    => 'PA',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'PR' => array(
								array(
										'country'  => 'US',
										'state'    => 'PR',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'RI' => array(
								array(
										'country'  => 'US',
										'state'    => 'RI',
										'rate'     => '7.0000',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'SC' => array(
								array(
										'country'  => 'US',
										'state'    => 'SC',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'SD' => array(
								array(
										'country'  => 'US',
										'state'    => 'SD',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'TN' => array(
								array(
										'country'  => 'US',
										'state'    => 'TN',
										'rate'     => '7.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'TX' => array(
								array(
										'country'  => 'US',
										'state'    => 'TX',
										'rate'     => '6.2500',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'UT' => array(
								array(
										'country'  => 'US',
										'state'    => 'UT',
										'rate'     => '5.9500',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'VT' => array(
								array(
										'country'  => 'US',
										'state'    => 'VT',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'VA' => array(
								array(
										'country'  => 'US',
										'state'    => 'VA',
										'rate'     => '5.3000',
										'name'     => 'State Tax',
										'shipping' => false
								)
						),
						'WA' => array(
								array(
										'country'  => 'US',
										'state'    => 'WA',
										'rate'     => '6.5000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'WV' => array(
								array(
										'country'  => 'US',
										'state'    => 'WV',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'WI' => array(
								array(
										'country'  => 'US',
										'state'    => 'WI',
										'rate'     => '5.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						),
						'WY' => array(
								array(
										'country'  => 'US',
										'state'    => 'WY',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => true
								)
						)
				)
		),
		'ZA' => array(
				'currency_code'  => 'ZAR',
				'currency_pos'   => 'left',
				'thousand_sep'   => ',',
				'decimal_sep'    => '.',
				'num_decimals'   => 2,
				'weight_unit'    => 'kg',
				'dimension_unit' => 'cm',
				'tax_rates'      => array(
						'' => array(
								array(
										'country'  => 'ZA',
										'state'    => '',
										'rate'     => '14.0000',
										'name'     => 'VAT',
										'shipping' => true
								)
						)
				)
		)
	);
}

/**
 * Checks if Flexible Invoices for WooCommerce is active
 *
 */
function is_flexible_invoices_woocommerce_active() {
	return is_plugin_active( 'flexible-invoices-woocommerce/flexible-invoices-woocommerce.php' );
}

/**
 * Checks if plugin is active
 *
 */
if ( ! function_exists( 'wpdesk_is_plugin_active' ) ) {
	function wpdesk_is_plugin_active( $plugin_file ) {

		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		return in_array( $plugin_file, $active_plugins ) || array_key_exists( $plugin_file, $active_plugins );
	}
}

if ( ! function_exists( 'should_enable_wpdesk_tracker' ) ) {
	function should_enable_wpdesk_tracker() {
		$tracker_enabled = true;
		if ( ! empty( $_SERVER['SERVER_ADDR'] ) && $_SERVER['SERVER_ADDR'] === '127.0.0.1' ) {
			$tracker_enabled = false;
		}

		return apply_filters( 'wpdesk_tracker_enabled', $tracker_enabled );
	}
}
