<?php

namespace WPDeskFIVendor\WPDesk\Helper\Integration;

use WPDeskFIVendor\WPDesk\Helper\Page\SettingsPage;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
/**
 * Integrates WP Desk main settings page with WordPress
 *
 * @package WPDesk\Helper
 */
class SettingsIntegration implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable, \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\HookableCollection
{
    use HookableParent;
    /** @var SettingsPage */
    private $settings_page;
    public function __construct(\WPDeskFIVendor\WPDesk\Helper\Page\SettingsPage $settingsPage)
    {
        $this->add_hookable($settingsPage);
    }
    /**
     * @return void
     */
    public function hooks()
    {
        $this->hooks_on_hookable_objects();
    }
}
