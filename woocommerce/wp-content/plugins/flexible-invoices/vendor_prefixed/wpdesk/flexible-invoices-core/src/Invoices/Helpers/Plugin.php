<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;

/**
 * Plugin helpers functions.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Helpers
 */
class Plugin
{
    /**
     * @param string $plugin
     *
     * @return bool
     */
    public static function is_active($plugin)
    {
        if (self::is_function_exists('is_plugin_active_for_network')) {
            if (\is_plugin_active_for_network($plugin)) {
                return \true;
            }
        }
        return \in_array($plugin, (array) \get_option('active_plugins', array()));
    }
    /**
     * @param string $name
     *
     * @return bool
     */
    public static function is_function_exists($name)
    {
        return \function_exists($name);
    }
}
