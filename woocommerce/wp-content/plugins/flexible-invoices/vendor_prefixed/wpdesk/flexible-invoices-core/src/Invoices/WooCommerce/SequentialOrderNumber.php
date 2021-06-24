<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
class SequentialOrderNumber implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Meta name for order number.
     */
    const META_NAME_ORDER_NUMBER = '_order_number';
    const OLD_NAMESPACE = 'inspire_invoices_woocommerce';
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
     * Fires hooks.
     */
    public function hooks()
    {
        if ('yes' === $this->settings->get('woocommerce_sequential_orders')) {
            $function = 'option_inspire_invoices_start_invoice_number';
            \add_filter('option_inspire_invoices_order_start_invoice_number', array($this, $function), 10, 2);
            \add_filter('option_inspire_invoices_correction_start_invoice_number', array($this, $function), 10, 2);
            \add_filter('option_inspire_invoices_proforma_start_invoice_number', array($this, $function), 10, 2);
            \add_action('wp_insert_post', array($this, 'set_order_num_action'), 10, 2);
            \add_action('woocommerce_process_shop_order_meta', array($this, 'set_order_num_action'), 10, 2);
            \add_filter('woocommerce_order_number', array($this, 'order_number_filter'), 10, 2);
            \add_filter('woocommerce_shop_order_search_fields', array($this, 'search_using_order_number_filter'));
            $this->install_numbering();
        }
    }
    /**
     * Add _order_number to search fields.
     *
     * @param array $search_fields Search fields.
     *
     * @return array
     *
     * @internal You should not use this directly from another application
     */
    public function search_using_order_number_filter($search_fields)
    {
        $search_fields[] = '_order_number';
        return $search_fields;
    }
    /**
     * Order number replacement filter.
     *
     * @param string    $order_number Order number.
     * @param \WC_Order $order        Order.
     *
     * @return string
     *
     * @internal You should not use this directly from another application
     */
    public function order_number_filter($order_number, \WC_Order $order)
    {
        $replaced_num = $order->get_meta(self::META_NAME_ORDER_NUMBER, \true);
        if (!empty($replaced_num)) {
            return $replaced_num;
        }
        return $order_number;
    }
    /**
     * @param mixed  $value
     * @param string $option
     *
     * @return string
     *
     * @internal You should not use this directly from another application
     */
    public function option_inspire_invoices_start_invoice_number($value, $option)
    {
        global $wpdb;
        $wpdb->query($wpdb->prepare("UPDATE {$wpdb->options} SET option_value = option_value WHERE option_name = %s", $option));
        $row = $wpdb->get_row($wpdb->prepare("SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1", $option));
        if (\is_object($row)) {
            $value = $row->option_value;
        }
        return $value;
    }
    /**
     * Invoices WooCommerce method.
     *
     * Set order num action
     *
     * @param int      $post_id Post ID.
     * @param \WP_Post $post    Post object.
     *
     * @internal You should not use this directly from another application
     */
    public function set_order_num_action($post_id, $post)
    {
        global $wpdb;
        if ('shop_order' === $post->post_type && 'auto-draft' !== $post->post_status) {
            $order_number = \get_post_meta($post_id, self::META_NAME_ORDER_NUMBER, \true);
            if (!$order_number) {
                if ($this->settings->get('woocommerce_sequential_orders') === 'yes') {
                    // Attempt the query up to 3 times for a much higher success rate if it fails (due to Deadlock).
                    $success = \false;
                    for ($i = 0; $i < 3 && !$success; $i++) {
                        // This seems to me like the safest way to avoid order number clashes.
                        $success = $wpdb->query('INSERT INTO ' . $wpdb->postmeta . ' (post_id,meta_key,meta_value) SELECT ' . $post_id . ',"_order_number",if(max(cast(meta_value as UNSIGNED)) is null,1,max(cast(meta_value as UNSIGNED))+1) from ' . $wpdb->postmeta . ' where meta_key="_order_number"');
                        //phpcs:ignore
                    }
                } else {
                    \update_post_meta($post_id, self::META_NAME_ORDER_NUMBER, $post_id);
                }
            }
        }
    }
    /**
     * Invoices WooCommerce method.
     *
     * Install invoice numbering.
     *
     * @internal You should not use this directly from another application
     */
    public function install_numbering()
    {
        $installed_version = \get_option(self::OLD_NAMESPACE);
        if (!$installed_version) {
            $orders = \get_posts(array('numberposts' => '', 'post_type' => 'shop_order', 'nopaging' => \true));
            if (\is_array($orders)) {
                foreach ($orders as $order) {
                    if (\get_post_meta($order->ID, self::META_NAME_ORDER_NUMBER, \true) == '') {
                        \add_post_meta($order->ID, self::META_NAME_ORDER_NUMBER, $order->ID);
                    }
                }
            }
        }
        if (1 !== $installed_version) {
            \update_option(self::OLD_NAMESPACE, 1);
        }
    }
}
