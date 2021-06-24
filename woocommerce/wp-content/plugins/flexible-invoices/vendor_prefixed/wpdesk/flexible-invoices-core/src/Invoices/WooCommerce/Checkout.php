<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * WooCommerce Checkout.
 */
class Checkout implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @param Settings $settings
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings)
    {
        $this->settings = $settings;
    }
    /**
     * Fires hooks
     */
    public function hooks()
    {
        \add_action('woocommerce_after_order_notes', array($this, 'add_wpml_user_session_lang'), 10, 1);
        \add_action('woocommerce_checkout_update_order_meta', array($this, 'save_wpml_user_session_lang'), 10, 1);
        \add_action('woocommerce_checkout_update_user_meta', array($this, 'save_customer_vat_field'), 10, 2);
        if ('yes' === $this->settings->get('woocommerce_add_nip_field')) {
            \add_action('woocommerce_checkout_process', array($this, 'validate_vat_number'), 10, 1);
        }
    }
    /**
     * Update customer vat number.
     *
     * @param int   $user_id   User ID.
     * @param array $post_data Post data.
     *
     * @internal You should not use this directly from another application
     */
    public function save_customer_vat_field($user_id, $post_data)
    {
        if (!empty($post_data['billing_vat_number'])) {
            \update_user_meta($user_id, 'vat_number', \sanitize_text_field($post_data['billing_vat_number']));
        }
    }
    /**
     * @param array $args
     *
     * @internal You should not use this directly from another application
     */
    public function validate_vat_number($args)
    {
        if (isset($_POST['billing_vat_number']) && !empty($_POST['billing_vat_number'])) {
            $vat_number = \wp_unslash(\trim($_POST['billing_vat_number']));
            if ($this->settings->get('woocommerce_validate_nip') === 'yes') {
                if (!\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\ValidateVatNumber::is_valid($vat_number)) {
                    $country = \WC()->customer->get_billing_country();
                    $woocommerce_default_country = \get_option('woocommerce_default_country', 0);
                    if (!\in_array($woocommerce_default_country, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\ValidateVatNumber::COUNTRY_ISO_SLUG) || $woocommerce_default_country == $country) {
                        \wc_add_notice(\sprintf(\__('Please enter a valid %s. Do not enter hyphens or spaces. Optionally add country prefix (EU VAT Number).', 'flexible-invoices'), $this->settings->get('woocommerce_nip_label')), 'error');
                    } else {
                        \wc_add_notice(\sprintf(\__('Please enter a valid %s without hyphens and spaces, with valid country prefix (EU VAT Number).', 'flexible-invoices'), $this->settings->get('woocommerce_nip_label')), 'error');
                    }
                }
            }
        }
    }
    /**
     * @param string $checkout
     *
     * @internal You should not use this directly from another application
     */
    public function add_wpml_user_session_lang($checkout)
    {
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::is_wpml_active()) {
            global $sitepress;
            echo '<input type="hidden" class="input-hidden" name="wpml_user_lang" id="wpml_user_lang" value="' . $sitepress->get_current_language() . '">';
        }
    }
    /**
     * @param int $order_id
     *
     * @internal You should not use this directly from another application
     */
    public function save_wpml_user_session_lang($order_id)
    {
        if (isset($_POST['wpml_user_lang']) && !empty($_POST['wpml_user_lang'])) {
            \update_post_meta($order_id, 'wpml_user_lang', \sanitize_text_field($_POST['wpml_user_lang']));
        }
    }
}
