<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WC_Order;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
/**
 * Added custom string for order formatted data.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce
 */
class FormattedOrderMeta implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @param Settings $settings
     * @param Renderer $renderer
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer)
    {
        $this->settings = $settings;
        $this->renderer = $renderer;
    }
    /**
     * Fires hooks
     */
    public function hooks()
    {
        \add_filter('woocommerce_ajax_get_customer_details', [$this, 'get_customer_details'], 10);
    }
    /**
     * Get VAT number for customer details in order
     *
     * @param array $data Customer details.
     *
     * @return array
     *
     * @internal You should not use this directly from another application
     */
    public function get_customer_details($data)
    {
        $vat_number_value = '';
        foreach ($data['meta_data'] as $meta_data) {
            $meta = $meta_data->get_data();
            if ('vat_number' === $meta['key']) {
                $vat_number_value = $meta['value'];
            }
        }
        $data['billing']['vat_number'] = $vat_number_value;
        return $data;
    }
}
