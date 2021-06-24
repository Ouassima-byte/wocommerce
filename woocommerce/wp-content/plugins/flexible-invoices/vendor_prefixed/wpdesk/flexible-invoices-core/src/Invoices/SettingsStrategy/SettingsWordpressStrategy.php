<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
/**
 * WordPress settings.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Strategy
 */
class SettingsWordpressStrategy extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\AbstractSettingsStrategy
{
    /**
     * @param Settings $settings
     */
    /**
     * @param Settings $settings
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings)
    {
        parent::__construct($settings);
    }
    /**
     * @return array
     */
    public function get_order_statuses()
    {
        return ['' => \__('Do not issue', 'flexible-invoices')];
    }
}
