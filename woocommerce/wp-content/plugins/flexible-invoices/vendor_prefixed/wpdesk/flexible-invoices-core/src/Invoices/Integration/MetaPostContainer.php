<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Containers\MetaContainer;
/**
 * Simple post meta container.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class MetaPostContainer implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Containers\MetaContainer
{
    /**
     * @var int
     */
    private $post_id;
    /**
     * @param int $post_id
     */
    public function __construct($post_id)
    {
        $this->post_id = (int) $post_id;
    }
    /**
     * @param string $name Meta key.
     *
     * @return mixed
     */
    public function get($name)
    {
        return \get_post_meta($this->post_id, $name, \true);
    }
    /**
     * @param string $name    Meta key.
     * @param mixed  $default Default value.
     *
     * @return mixed
     */
    public function get_fallback($name, $default = null)
    {
        $value = $this->get($name);
        if (empty($value) && $default !== null) {
            return $default;
        }
        return $value;
    }
    /**
     * @param string $name  Meta key.
     * @param mixed  $value Value.
     *
     * @return string|null
     */
    public function set($name, $value)
    {
        return \update_post_meta($this->post_id, $name, $value);
    }
    /**
     * @param string $name Meta key.
     *
     * @return bool
     */
    public function has($name)
    {
        $value = \get_post_meta($this->post_id, $name, \true);
        return !empty($value);
    }
    /**
     * @param string $name Meta key.
     *
     * @return bool
     */
    public function delete($name)
    {
        return \delete_post_meta($this->post_id, $name);
    }
}
