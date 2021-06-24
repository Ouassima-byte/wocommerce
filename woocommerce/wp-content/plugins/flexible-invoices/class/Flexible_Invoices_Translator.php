<?php


class Flexible_Invoices_Translator {

	const DEFAULT_LANG = 'en';

	const SUPPORTED_LANGS = [
		'nl' => 'nl_NL',
		'pl' => 'pl_PL',
	];

	const STRINGS = [
		'Issue date',
		'Due date',
		'Payment method',
		'Seller',
		'Buyer',
		'#',
		'Name',
		'SKU',
		'Quantity',
		'Unit',
		'Tax rate',
		'Tax amount',
		'Gross amount',
		'Gross price',
		'VAT Number',
		'Bank',
		'Account number',
		'Price',
		'Net price',
		'Amount',
		'Net amount',
		'Total',
		'Paid',
		'Due',
		'Including',
		'Buyer signature',
		'Seller signature',
		'Notes',
		'Order number',
		'Related to invoice',
		'Invoice issue date',
		'Before correction',
		'After correction',
		'Bank transfer',
		'Cash',
		'Other',

	];

	const STRINGS_WOO = [
		'Exchange rate European Central Bank of the day: %s.',
		'Correction reason: %s',
		'Order number: %s',
		'item',
		'You have entered an invalid VAT number (%1$s) for your billing country (%2$s).',
		'Your IP Address (%1$s) does not match your billing country (%2$s). European VAT laws require your IP address to match your billing country when purchasing digital goods in the EU. Please confirm you are located within your billing country using the checkbox below.',
		'<strong>%1$s plugin from version %2$s supports MOSS.</strong> <a href=\"%3$s\" target=\"_blank\">Here</a> you can read how to configure it. You no longer need any other plugins for invoicing with MOSS standard. Please deactivate them.',
		'Tax rounding at subtotals is off. Please check \"Rounding\" option for invoice plugin to function properly. To find it click <a href=\"%s\">here</a> or go to Integration tax settings.',
		'I am established, have my permanent address, or usually reside within <strong>%s</strong>.',
		'I am already registered in VAT EU.',
		'B2B',
		'Yes',
		'No',
		'Validation was not possible',
		'Self declared',
		'IP Address',
		'Unknown',
		'IP Country',
		'(self-declared)',
		'Billing Country',
		'Invalid country code',
		'Error communicating with the VAT validation server - please try again',
		'EU VAT',
		'This order is out of scope for EU VAT.',
		'VAT ID',
	];

	const FIELDS = [
		'inspire_invoices_order_number_prefix' => 'Invoice',
		'inspire_invoices_order_number_suffix' => '/{MM}/{YYYY}',
		'inspire_invoices_date_of_sale'        => 'Date of sale',
		'inspire_invoices_invoices_notice'     => ' ',
		'inspire_invoices_correction_prefix'   => 'Corrected invoice ',
		'inspire_invoices_correction_suffix'   => '/{MM}/{YYYY}',
		'inspire_invoices_correction_reason'   => 'Refund'
	];

	const FIELDS_WOO = [
		'inspire_invoices_woocommerce_nip_label'                  => 'VAT Number',
		'inspire_invoices_woocommerce_nip_placeholder'            => 'Enter VAT Number',
		'inspire_invoices_woocommerce_reverse_charge_description' => 'Reverse charge',
		'inspire_invoices_woocommerce_vat_moss_description'       => ''
	];

	private static $plugin_info;

	private static $text_domain = 'flexible-invoices';
	private static $text_domain_woo = 'flexible-invoices';

	private static $force_lang;

	public static function reset_translations() {
		delete_option( 'flexible-invoices-register-strings' );
		delete_option( 'flexible-invoices-register-woocommerce-strings' );
		delete_option( 'flexible-invoices-load-translations' );
		delete_option( 'flexible-invoices-load-woocommerce-translations' );
	}

	public static function init( WPDeskFIVendor\WPDesk_Plugin_Info $plugin_info ) {
		self::$plugin_info = $plugin_info;
		self::$text_domain = $plugin_info->get_text_domain();
		add_action( 'wp_loaded', array( 'Flexible_Invoices_Translator', 'init_wpml' ) );

	}

	public static function switch_lang( $lang ) {
		if ( function_exists( 'icl_get_languages' ) ) {
			if ( array_key_exists( $lang, icl_get_languages() ) ) {
				do_action( 'wpml_switch_language', $lang );
			}
		}
	}

	public static function init_wpml() {
		if ( ! wp_doing_ajax() && ! defined( 'DOING_CRON' ) && is_admin() ) {
			self::register_strings();
			self::register_meta_strings();
			self::load_translations();

			if ( self::is_plugin_active( 'flexible-invoices-woocommerce/flexible-invoices-woocommerce.php' ) ) {
				self::register_woocommerce_strings();
				self::register_woocommerce_meta_strings();
				self::load_woocommerce_translations();

			}
		}
	}

	public static function register_strings() {
		if ( function_exists( 'icl_register_string' ) ) {
			if ( empty( get_option( 'flexible-invoices-register-strings' ) ) ) {
				foreach ( self::STRINGS as $string ) {
					icl_register_string( self::$text_domain, self::$text_domain . ' - ' . $string, $string, false, self::DEFAULT_LANG );
				}
				update_option( 'flexible-invoices-register-strings', 1 );
			}
		}
	}

	public static function register_woocommerce_strings() {
		if ( function_exists( 'icl_register_string' ) ) {
			if ( empty( get_option( 'flexible-invoices-register-woocommerce-strings' ) ) ) {
				foreach ( self::STRINGS_WOO as $string ) {
					icl_register_string( self::$text_domain_woo, self::$text_domain_woo . ' - ' . $string, $string, false, self::DEFAULT_LANG );
				}
				update_option( 'flexible-invoices-register-woocommerce-strings', 1 );
			}
		}
	}

	public static function register_meta_strings() {
		if ( function_exists( 'icl_register_string' ) ) {
			global $sitepress;
			if ( $sitepress instanceof \SitePress ) {
				foreach ( self::FIELDS as $meta_key => $meta_val ) {
					$value = get_option( $meta_key, __( $meta_val, self::$text_domain ) );
					icl_register_string( self::$text_domain, $meta_key, $value, true, $sitepress->get_default_language() );
				}
			}
		}
	}

	public static function register_woocommerce_meta_strings() {
		if ( function_exists( 'icl_register_string' ) ) {
			global $sitepress;
			if ( $sitepress instanceof \SitePress ) {
				foreach ( self::FIELDS_WOO as $meta_key => $meta_val ) {
					$value = get_option( $meta_key, __( $meta_val, self::$text_domain_woo ) );
					icl_register_string( self::$text_domain_woo, $meta_key, $value, true, $sitepress->get_default_language() );
				}
			}
		}
	}

	public static function load_translations() {
		$load = (int) get_option( 'flexible-invoices-load-translations', 0 );
		if ( $load < 2 && ! empty( get_option( 'flexible-invoices-register-strings' ) ) ) {
			self::load_translation_domain( self::$text_domain, self::FIELDS );
			$load ++;
			update_option( 'flexible-invoices-load-translations', $load );
		}
	}

	public static function load_woocommerce_translations() {
		$load = (int) get_option( 'flexible-invoices-load-woocommerce-translations', 0 );
		if ( $load < 2 && ! empty( get_option( 'flexible-invoices-register-woocommerce-strings' ) ) ) {
			self::load_translation_domain( self::$text_domain_woo, self::FIELDS_WOO );
			$load ++;
			update_option( 'flexible-invoices-load-woocommerce-translations', $load );
		}
	}

	private static function load_translation_domain( $text_domain, $fields ) {
		if ( function_exists( 'icl_get_string_translations' ) ) {
			$loaded_mo = self::load_mo_translations( $text_domain );
			foreach ( icl_get_string_translations() as $string_id => $icl_string ) {
				if ( $icl_string['context'] === $text_domain ) {
					if ( array_key_exists( $icl_string['name'], $fields ) ) {
						self::translate_registered_meta_string( $loaded_mo, $text_domain, $string_id, $icl_string, $fields );
					} else {
						self::translate_registered_string( $loaded_mo, $text_domain, $string_id, $icl_string );
					}
					icl_update_string_status( $string_id );
				}
			}
		}
	}

	public static function translate( $string, $textdomain = '', $name = '' ) {
		global $sitepress;
		$textdomain = empty( $textdomain ) ? self::$text_domain : $textdomain;
		$name       = ! empty( $name ) ? $name : $textdomain . ' - ' . $string;

		if ( $sitepress instanceof \SitePress ) {
			$current_lang = self::get_translate_lang();
			$translated   = apply_filters( 'wpml_translate_single_string', $string, $textdomain, $name, $current_lang );
		} else {
			$translated = __( $string, $textdomain );
		}

		return $translated;
	}

	public static function translate_meta( $meta, $default = '', $textdomain = '' ) {
		global $sitepress;
		$textdomain = empty( $textdomain ) ? self::$text_domain : $textdomain;
		$string     = get_option( $meta, $default );
		if ( $sitepress instanceof \SitePress ) {
			$active_lang  = self::get_active_lang();
			$current_lang = self::get_translate_lang();
			do_action( 'wpml_switch_language', $sitepress->get_default_language() );
			$translated = apply_filters( 'wpml_translate_single_string', $string, $textdomain, $meta, $current_lang );
			do_action( 'wpml_switch_language', $active_lang );
		} else {
			$translated = __( $string, $textdomain );
		}

		return $translated;
	}

	public static function is_default_language() {
		global $sitepress;
		if ( $sitepress instanceof \SitePress ) {
			return $sitepress->get_current_language() == $sitepress->get_default_language();
		}

		return true;
	}

	public static function get_translated_input_field( $name, $value, $id = '', $class = '', $textdomain = '' ) {
		global $sitepress;
		$textdomain = empty( $textdomain ) ? self::$text_domain : $textdomain;
		$string     = '';

		if ( Flexible_Invoices_Translator::is_default_language() ) {
			$string = '<input value="' . $value . '" id="' . $id . '" name="' . $name . '" class="' . $class . '" type="text" />';
		} else {
			$translated_value = $value;
			if ( $sitepress instanceof \SitePress ) {
				$current_lang     = $sitepress->get_current_language();
				$translated_value = apply_filters( 'wpml_translate_single_string', $value, $textdomain, $id, $current_lang );
			}
			$string = '<input value= "' . $translated_value . '" id="' . $id . '" name="" class="' . $class . '" type="text" disabled />';
			$string .= '<input value= "' . $value . '" name="' . $name . '" type="hidden" />';
		}

		return $string;
	}

	public static function get_translated_textarea_field( $name, $value, $id = '', $class = '', $textdomain = '' ) {
		global $sitepress;
		$textdomain = empty( $textdomain ) ? self::$text_domain : $textdomain;
		$string     = '';

		if ( Flexible_Invoices_Translator::is_default_language() ) {
			$string = '<textarea id="' . $id . '" name="' . $name . '" class="' . $class . '">' . $value . '</textarea>';
		} else {
			$translated_value = $value;
			if ( $sitepress instanceof \SitePress ) {
				$current_lang     = $sitepress->get_current_language();
				$translated_value = apply_filters( 'wpml_translate_single_string', $value, $textdomain, $id, $current_lang );
			}
			$string = '<textarea id="' . $id . '" class="' . $class . '" disabled>' . $translated_value . '</textarea>';
			$string .= '<textarea name="' . $name . '" class="hidden" readonly>' . $value . '</textarea>';
		}

		return $string;
	}

	public static function is_wpml_active() {
		global $sitepress;

		return $sitepress instanceof \SitePress;
	}

	public static function get_active_lang() {
		if ( self::is_wpml_active() ) {
			global $sitepress;

			return $sitepress->get_current_language();
		}

		return get_bloginfo( 'language' );
	}

	public static function set_translate_lang( $lang ) {
		if ( function_exists( 'icl_get_languages' ) ) {
			if ( array_key_exists( $lang, icl_get_languages() ) ) {
				self::$force_lang = $lang;
			}
		}
	}

	private static function get_translate_lang() {
		if ( isset( self::$force_lang ) && ! empty( self::$force_lang ) ) {
			return self::$force_lang;
		}

		return self::get_active_lang();
	}

	private static function load_mo_translations( $textdomain ) {
		global $l10n;
		$loaded_mo  = array();
		$plugin_dir = '';

		if ( self::$text_domain === $textdomain ) {
			$plugin_dir = self::$plugin_info->get_plugin_dir();
		} elseif ( self::$text_domain_woo === $textdomain ) {
			if ( self::is_plugin_active( 'flexible-invoices-woocommerce/flexible-invoices-woocommerce.php' ) ) {
				$plugin_file = WP_PLUGIN_DIR . '/flexible-invoices-woocommerce/flexible-invoices-woocommerce.php';
				if ( file_exists( $plugin_file ) ) {
					$plugin_dir = plugin_dir_path( $plugin_file );
				}
			}
		}

		if ( ! empty( $plugin_dir ) ) {
			foreach ( self::SUPPORTED_LANGS as $lang_key => $locale ) {
				unset( $l10n[ $textdomain ] );
				$path = $plugin_dir . '/lang/' . $textdomain . '-' . $locale . '.mo';
				load_textdomain( $textdomain, $path );
				if ( isset( $loaded_mo[ $lang_key ] ) ) {
					$loaded_mo[ $lang_key ] = $l10n[ $textdomain ];
				}
			}
		}

		return $loaded_mo;
	}

	private static function translate_registered_string( $loaded_mo, $textdomain, $string_id, $icl_string ) {
		global $l10n;
		$translator_id = ( current_user_can( 'manage_options' ) && get_current_user_id() > 0 ) ? get_current_user_id() : null;

		if ( isset( $l10n[ $textdomain ] ) ) {
			$backup = $l10n[ $textdomain ];
			if ( ! empty( $loaded_mo ) && $textdomain === $icl_string['context'] && function_exists( 'icl_add_string_translation' ) ) {
				foreach ( $loaded_mo as $lang_key => $lang_array ) {
					unset( $l10n[ $textdomain ] );
					$l10n[ $textdomain ] = $lang_array;
					$icl_st_complete     = __( $icl_string['value'], $textdomain ) != $icl_string['value'] ? ICL_TM_COMPLETE : ICL_TM_NOT_TRANSLATED;
					icl_add_string_translation(
						$string_id,
						$lang_key,
						stripslashes( __( $icl_string['value'], $textdomain ) ),
						$icl_st_complete,
						$translator_id
					);
				}
			}
			$l10n[ $textdomain ] = $backup;
		}
	}

	private static function translate_registered_meta_string( $loaded_mo, $textdomain, $string_id, $icl_string, $fields ) {
		global $l10n;
		global $sitepress;
		$translator_id = ( current_user_can( 'manage_options' ) && get_current_user_id() > 0 ) ? get_current_user_id() : null;

		if ( isset( $l10n[ $textdomain ] ) ) {
			$backup = $l10n[ $textdomain ];

			if ( ! empty( $loaded_mo ) && $textdomain === $icl_string['context'] && function_exists( 'icl_add_string_translation' ) ) {
				$is_en_lang = $sitepress->get_default_language() === self::DEFAULT_LANG;
				foreach ( $loaded_mo as $lang_key => $lang_array ) {
					if ( false === $is_en_lang && $sitepress->get_default_language() !== $lang_key ) {
						unset( $l10n[ $textdomain ] );
						$l10n[ $textdomain ] = $lang_array;
						$icl_st_complete     = __( $icl_string['value'], $textdomain ) != $icl_string['value'] ? ICL_TM_COMPLETE : ICL_TM_NOT_TRANSLATED;
						icl_add_string_translation(
							$string_id,
							$lang_key,
							stripslashes( __( $icl_string['value'], $textdomain ) ),
							$icl_st_complete,
							$translator_id
						);
					}
				}

				if ( false === $is_en_lang ) {
					icl_add_string_translation(
						$string_id,
						self::DEFAULT_LANG,
						stripslashes( $fields[ $icl_string['name'] ] ),
						ICL_TM_COMPLETE,
						$translator_id
					);
				}

			}

			$l10n[ $textdomain ] = $backup;
		}
	}

	private static function is_plugin_active( $plugin ) {
		if ( function_exists( 'is_plugin_active_for_network' ) ) {
			if ( is_plugin_active_for_network( $plugin ) ) {
				return true;
			}
		}

		return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
	}
}
