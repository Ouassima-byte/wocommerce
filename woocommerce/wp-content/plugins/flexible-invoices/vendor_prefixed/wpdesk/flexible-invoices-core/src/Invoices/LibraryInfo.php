<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore;

/**
 * Define new plugin info for library.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore
 */
class LibraryInfo
{
    const LIBRARY_DIR = 'vendor_prefixed/wpdesk/flexible-invoices-core/';
    const ASSETS_DIR = 'vendor_prefixed/wpdesk/flexible-invoices-core/assets/';
    const TEMPLATES_DIR = 'vendor_prefixed/wpdesk/flexible-invoices-core/templates/';
    /**
     * @var \WPDesk_Plugin_Info
     */
    private $plugin_info;
    /**
     * @param \WPDesk_Plugin_Info $plugin_info
     */
    public function __construct(\WPDeskFIVendor\WPDesk_Plugin_Info $plugin_info)
    {
        $this->plugin_info = $plugin_info;
    }
    /**
     * @return string
     */
    public function get_assets_dir()
    {
        return \trailingslashit($this->plugin_info->get_plugin_dir()) . self::ASSETS_DIR;
    }
    /**
     * @return string
     */
    public function get_assets_url()
    {
        return \trailingslashit($this->plugin_info->get_plugin_url()) . self::ASSETS_DIR;
    }
    /**
     * @return string
     */
    public function get_template_dir()
    {
        return \trailingslashit($this->plugin_info->get_plugin_dir()) . self::TEMPLATES_DIR;
    }
    /**
     * @return string
     */
    public function get_library_dir()
    {
        return \trailingslashit($this->plugin_info->get_plugin_dir()) . self::LIBRARY_DIR;
    }
    /**
     * @return string
     */
    public function get_library_url()
    {
        return \trailingslashit($this->plugin_info->get_plugin_url()) . self::LIBRARY_DIR;
    }
    /**
     * @return string
     */
    public function get_plugin_dir()
    {
        return \trailingslashit($this->plugin_info->get_plugin_dir());
    }
    /**
     * @return string
     */
    public function get_plugin_url()
    {
        return \trailingslashit($this->plugin_info->get_plugin_url());
    }
    /**
     * @return string
     */
    public function get_plugin_version()
    {
        return $this->plugin_info->get_version();
    }
    /**
     * @return \WPDesk_Plugin_Info
     */
    public function get_plugin_info()
    {
        return $this->plugin_info;
    }
}
