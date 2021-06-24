<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields\SubTabInterface;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
/**
 * General Settings Tab Page.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs
 */
final class WooCommerceSettings extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\FieldSettingsTab
{
    private $form_fields = [];
    /**
     * @var SettingsStrategy
     */
    private $strategy;
    /**
     * @param SettingsStrategy $strategy
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy)
    {
        $this->strategy = $strategy;
        $this->set_sub_tab_forms();
    }
    /**
     * Set document fields form.
     */
    private function set_sub_tab_forms()
    {
        /**
         * @var WooCommerceFields\SubTabInterface[] $settings
         */
        $woocommerce_tabs = ['general' => new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields\GeneralSettingsFields(), 'checkout' => new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields\CheckoutSettingsFields($this->strategy), 'moss' => new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields\MossSettingsFields($this->strategy)];
        /**
         * Definitions of settings for WooCommerce tab.
         *
         * @param SubTabInterface[] $woocommerce_tabs WooCommerce Settings Tab.
         *
         * @since (1.2.0)
         *
         */
        $settings = (array) \apply_filters('fi/core/settings/woocommerce', $woocommerce_tabs);
        foreach ($settings as $setting) {
            if ($setting instanceof \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields\SubTabInterface) {
                $this->form_fields[$setting::get_tab_slug()] = $setting->get_fields();
            }
        }
    }
    /**
     * @return array|\WPDesk\Forms\Field[]
     */
    public function get_fields()
    {
        $fields = [];
        foreach ($this->form_fields as $form) {
            foreach ($form as $field) {
                $fields[] = $field;
            }
        }
        return $fields;
    }
    /**
     * @return string
     */
    public static function get_tab_slug()
    {
        return 'woocommerce';
    }
    /**
     * @return string
     */
    public function get_tab_name()
    {
        return \__('WooCommerce', 'flexible-invoices');
    }
}
