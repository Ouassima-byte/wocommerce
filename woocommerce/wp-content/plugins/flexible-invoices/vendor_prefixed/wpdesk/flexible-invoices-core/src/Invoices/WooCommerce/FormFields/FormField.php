<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields;

use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Define billing field.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields
 */
class FormField implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    const PRIORITY_FORMATTED_FIELDS = 999999;
    const PRIORITY_BILLING_FIELDS = 20;
    /**
     * @var string
     */
    private $field_id;
    /**
     * @var string
     */
    private $order_meta_field_id;
    /**
     * @var string
     */
    private $text_domain;
    /**
     * @var string
     */
    private $checkout_field_id;
    /**
     * @var string
     */
    private $add_field_after = 'billing_company';
    /**
     * @var string
     */
    private $add_admin_field_after = 'company';
    /**
     * @var bool
     */
    private $exclude_from_checkout = \false;
    /**
     * @var bool
     */
    private $required = \false;
    /**
     * @param string $field_id Field ID.
     */
    public function __construct($field_id)
    {
        $this->field_id = $field_id;
        $this->order_meta_field_id = '_billing_' . $field_id;
        $this->checkout_field_id = 'billing_' . $field_id;
    }
    /**
     * Hooks.
     */
    public function hooks()
    {
        if (!$this->exclude_from_checkout) {
            \add_filter('woocommerce_billing_fields', [$this, 'append_field_to_billing_fields']);
            \add_action('woocommerce_checkout_create_order', [$this, 'add_meta_data_to_order'], 10, 2);
            \add_filter('woocommerce_order_formatted_billing_address', [$this, 'add_field_to_formatted_billing_address'], static::PRIORITY_FORMATTED_FIELDS, 2);
            \add_filter('woocommerce_localisation_address_formats', [$this, 'add_field_to_localisation_address_formats'], 11);
            \add_filter('woocommerce_formatted_address_replacements', [$this, 'add_address_replacements'], 11, 2);
        }
        \add_filter('woocommerce_admin_billing_fields', [$this, 'add_admin_billing_field']);
    }
    public function set_required()
    {
        $this->required = \true;
    }
    /**
     * @return bool
     */
    public function get_required()
    {
        return $this->required;
    }
    /**
     * @param \WC_Order $order Order.
     *
     * @return mixed
     */
    public function get_from_order(\WC_Order $order)
    {
        return $order->get_meta($this->order_meta_field_id);
    }
    /**
     * Add billing field.
     *
     * @param array $fields Fields.
     *
     * @return array
     */
    public function append_field_to_billing_fields(array $fields)
    {
        $added = \false;
        $new_fields = array();
        foreach ($fields as $field_id => $field) {
            $new_fields[$field_id] = $field;
            if ($field_id === $this->add_field_after) {
                $added = \true;
                $field_priority = null;
                if (isset($field['priority']) && \is_numeric($field['priority'])) {
                    $field_priority = \intval($field['priority']);
                }
                $checkoutField = $this->prepare_checkout_field($field_priority);
                if (\is_array($checkoutField)) {
                    $new_fields[$this->checkout_field_id] = $checkoutField;
                }
            }
        }
        if (!$added) {
            $checkoutField = $this->prepare_checkout_field();
            if (\is_array($checkoutField)) {
                $new_fields[$this->checkout_field_id] = $checkoutField;
            }
        }
        return $new_fields;
    }
    /**
     * Prepare checkout field.
     *
     * @param null|int $field_priority Field priority
     *
     * @return bool|array
     */
    protected function prepare_checkout_field($field_priority = null)
    {
        return \false;
    }
    /**
     * Add metadata to order.
     *
     * @param \WC_Order $order Order.
     * @param array     $data  Data.
     */
    public function add_meta_data_to_order(\WC_Order $order, array $data)
    {
        if (isset($data[$this->checkout_field_id])) {
            $order->update_meta_data($this->order_meta_field_id, $data[$this->checkout_field_id]);
        }
    }
    /**
     * Add admin billing field.
     *
     * @param array $fields
     *
     * @return array
     */
    public function add_admin_billing_field(array $fields)
    {
        $added = \false;
        $new_fields = array();
        foreach ($fields as $field_id => $field) {
            $new_fields[$field_id] = $field;
            if ($field_id === $this->add_admin_field_after) {
                $added = \true;
                $new_fields[$this->field_id] = $this->prepare_admin_field();
            }
        }
        if (!$added) {
            $new_fields[$this->field_id] = $this->prepare_admin_field();
        }
        return $new_fields;
    }
    /**
     * @param array     $fields
     * @param \WC_Order $order
     *
     * @return array
     */
    public function add_field_to_formatted_billing_address($fields, $order)
    {
        $fields[$this->field_id] = $order->get_meta($this->get_order_meta_field_id());
        return $fields;
    }
    /**
     * Add fields to address template. Tries to add them only once.
     *
     * @param array $formats
     *
     * @return array
     */
    public function add_field_to_localisation_address_formats($formats)
    {
        $key_value = "{" . $this->field_id . "}";
        foreach ($formats as $country => $val) {
            if (\stripos($formats[$country], $key_value) === \false) {
                $formats[$country] = $val . "\n" . $key_value;
            }
        }
        return $formats;
    }
    /**
     * @param array $fields
     * @param array $args
     *
     * @return array
     */
    public function add_address_replacements($fields, $args)
    {
        $fields['{' . $this->field_id . '}'] = $args[$this->field_id];
        return $fields;
    }
    /**
     * @return bool
     */
    public function is_exclude_from_checkout()
    {
        return $this->exclude_from_checkout;
    }
    /**
     * @param bool $exclude_from_checkout
     */
    public function set_exclude_from_checkout($exclude_from_checkout)
    {
        $this->exclude_from_checkout = $exclude_from_checkout;
    }
    /**
     * Prepare admin field.
     *
     * @return bool|array
     */
    protected function prepare_admin_field()
    {
        return \false;
    }
    /**
     * @return string
     */
    public function get_field_id()
    {
        return $this->field_id;
    }
    /**
     * @return string
     */
    public function get_order_meta_field_id()
    {
        return $this->order_meta_field_id;
    }
    /**
     * @return string
     */
    public function get_text_domain()
    {
        return $this->text_domain;
    }
    /**
     * @return string
     */
    public function get_checkout_field()
    {
        return $this->checkout_field_id;
    }
    /**
     * @return string
     */
    public function get_add_field_after()
    {
        return $this->add_field_after;
    }
    /**
     * @return string
     */
    public function get_add_admin_field_after()
    {
        return $this->add_admin_field_after;
    }
}
