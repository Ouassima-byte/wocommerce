<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Beacon;

use WPDeskFIVendor\WPDesk\Beacon\BeaconPro;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Class BeaconLoaderAction, Beacon loader.
 * @package WPDesk\DropshippingXml\Action\Loader\Beacon
 */
class BeaconLoader implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var LibraryInfo
     */
    private $plugin_info;
    /**
     * @param LibraryInfo $plugin_info
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo $plugin_info)
    {
        $this->plugin_info = $plugin_info;
    }
    /**
     * Fire hooks.
     */
    public function hooks()
    {
        \add_action('init', [$this, 'init_beacon'], 10);
    }
    /**
     * Init beacon.
     */
    public function init_beacon()
    {
        $beacon_id = '17f6054b-a2fb-4ee7-8bb5-0c3cbad1ef6a';
        $beacon = new \WPDeskFIVendor\WPDesk\Beacon\BeaconPro($beacon_id, new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Beacon\BeaconShowStrategy(), $this->plugin_info->get_plugin_url() . 'vendor_prefixed/wpdesk/wp-helpscout-beacon/assets/');
        $beacon->hooks();
    }
}
