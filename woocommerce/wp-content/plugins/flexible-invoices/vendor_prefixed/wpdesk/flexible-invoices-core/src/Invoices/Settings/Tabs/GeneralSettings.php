<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\ImageInputField;
use WPDeskFIVendor\WPDesk\Forms\Field\Header;
use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;
use WPDeskFIVendor\WPDesk\Forms\Field\SelectField;
use WPDeskFIVendor\WPDesk\Forms\Field\SubmitField;
use WPDeskFIVendor\WPDesk\Forms\Field\TextAreaField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields\SubTabInterface;
/**
 * General Settings Tab Page.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs
 */
final class GeneralSettings extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\FieldSettingsTab
{
    /** @var string slug od administrator role */
    const ADMIN_ROLE = 'administrator';
    const CUSTOMER_ROLE = 'customer';
    const SUBSCRIBER_ROLE = 'subscriber';
    const SHOP_MANAGER_ROLE = 'shop_manager';
    /**
     * @return array
     */
    private function get_signature_users()
    {
        $users = [];
        $site_users = \get_users(['role__in' => ['administrator', 'editor']]);
        foreach ($site_users as $user) {
            $users[$user->ID] = $user->display_name ? $user->display_name : $user->user_login;
        }
        /**
         * Filters the default signature users passed to select in general settings.
         *
         * @param array $users      An array of prepared users.
         * @param array $site_users An array of site users.
         *
         * @since 1.3.5
         */
        return \apply_filters('fi/core/settings/general/signature_users', $users, $site_users);
    }
    /**
     * @return array
     */
    public function get_roles()
    {
        $roles = \wp_roles()->get_names();
        unset($roles[self::ADMIN_ROLE]);
        unset($roles[self::CUSTOMER_ROLE]);
        unset($roles[self::SUBSCRIBER_ROLE]);
        return (array) $roles;
    }
    /**
     * @return string
     */
    private function get_default_payment_methods()
    {
        return \implode("\n", array('bank-transfer' => \__('Bank transfer', 'flexible-invoices'), 'cash' => \__('Cash', 'flexible-invoices'), 'other' => \__('Other', 'flexible-invoices')));
    }
    private function get_beacon_translations() : array
    {
        return ['company' => 'Company', 'main' => 'Main Settings'];
    }
    /**
     * @return array|\WPDesk\Forms\Field[]
     */
    protected function get_fields()
    {
        $docs_link = \get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/docs/faktury-woocommerce-docs/' : 'https://docs.flexibleinvoices.com/';
        $docs_link .= '?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-invoices-docs-link';
        $beacon = $this->get_beacon_translations();
        return [(new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_label(\__('Company', 'flexible-invoices'))->set_description(\sprintf('<a href="%s" target="_blank">' . \__('Read user\'s manual →', 'flexible-invoices') . '</a>', $docs_link)), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('company_name')->set_label(\__('Company Name', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('regular-text hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\TextAreaField())->set_name('company_address')->set_label(\__('Company Address', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('large-text hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('company_nip')->set_label(\__('VAT Number', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('regular-text hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('bank_name')->set_label(\__('Bank Name', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('regular-text hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('account_number')->set_label(\__('Bank Account Number', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('regular-text hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\ImageInputField())->set_name('company_logo')->set_label(\__('Logo', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('regular-text hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_label(\__('General Settings', 'flexible-invoices')), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('show_signatures')->set_label(\__('Show Signatures', 'flexible-invoices'))->set_sublabel(\__('Enable if you want to display place for signatures.', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('signature_user')->set_label(\__('Seller signature', 'flexible-invoices'))->set_description(\__('Choose a user whose display name will be visible on the invoice in the signature section.', 'flexible-invoices'))->set_options($this->get_signature_users())->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('hide_vat')->set_label(\__('Tax Cells on Invoices', 'flexible-invoices'))->set_sublabel(\__('If tax is 0 hide all tax cells on PDF invoices.', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('hide_vat_number')->set_label(\__('Seller\'s VAT Number on Invoices', 'flexible-invoices'))->set_sublabel(\__('If tax is 0 hide seller\'s VAT Number on PDF invoices.', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\TextAreaField())->set_name('payment_methods')->set_label(\__('Payment Methods', 'flexible-invoices'))->set_default_value($this->get_default_payment_methods())->add_class('input-text wide-input hs-beacon-search')->set_attribute('data-beacon_search', $beacon['main']), (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('roles')->set_label(\__('Roles', 'flexible-invoices'))->set_description(\__('Select the User Roles that will be given permission to manage Invoices. The administrator has unlimited permissions.', 'flexible-invoices'))->set_options($this->get_roles())->add_class('select2')->set_multiple()->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\SubmitField())->set_name('save')->set_label(\__('Save changes', 'flexible-invoices'))->add_class('button-primary')];
    }
    /**
     * @return string
     */
    public static function get_tab_slug()
    {
        return 'general';
    }
    /**
     * @return string
     */
    public function get_tab_name()
    {
        return \__('General', 'flexible-invoices');
    }
}
