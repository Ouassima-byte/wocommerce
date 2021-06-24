<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings;

use WPDeskFIVendor\WPDesk\Persistence\PersistentContainer;
/**
 * WordPress settings container.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings
 */
class Settings implements \WPDeskFIVendor\WPDesk\Persistence\PersistentContainer
{
    private $prefix = 'inspire_invoices_';
    /**
     * @param $prefix
     */
    public function set_prefix($prefix)
    {
        $this->prefix = $prefix;
    }
    /**
     * @param string $name    Setting name.
     * @param null   $default Default value.
     *
     * @return string|null
     */
    public function get($name, $default = null)
    {
        $value = \get_option($this->prefix . $name, $default);
        $value = $this->get_real_checkbox_value($value);
        return $value;
    }
    /**
     * For backward compatibility, it returns the checkbox values for the new schema.
     *
     * @param string $value
     *
     * @return string
     */
    private function get_real_checkbox_value($value)
    {
        if (\is_string($value)) {
            if ($value === 'on') {
                return 'yes';
            }
            if ($value === 'off') {
                return 'no';
            }
        }
        return $value;
    }
    /**
     * @param string $name  Setting name.
     * @param null   $value Value.
     *
     * @return string|null
     */
    public function set($name, $value)
    {
        return \update_option($this->prefix . $name, $value);
    }
    /**
     * @param string $name Setting name.
     *
     * @return bool
     */
    public function has($name)
    {
        $option = \get_option($this->prefix . $name);
        return !empty($option);
    }
    /**
     * @param string $name Setting name.
     */
    public function delete($name)
    {
        \delete_option($this->prefix . $name);
    }
}
