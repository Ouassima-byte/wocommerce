<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields;

/**
 * Define invoice ask billing field.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields
 */
class InvoiceAsk extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields\FormField
{
    const CHECKBOX_CHECKED_VALUE = '1';
    const CHECKBOX_UNCHECKED_VALUE = '0';
    /**
     * Field label.
     *
     * @var string
     */
    protected $label;
    /**
     * @param string $field_id Field ID.
     * @param string $label    Label.
     */
    public function __construct($field_id, $label)
    {
        parent::__construct($field_id);
        $this->label = $label;
    }
    /**
     * @return string
     */
    public function get_label()
    {
        return $this->label;
    }
    /**
     * @param array $args
     *
     * @return bool
     */
    private function is_field_checked_from_args(array $args)
    {
        if (!empty($args[$this->get_field_id()]) && \strval($args[$this->get_field_id()]) === self::CHECKBOX_CHECKED_VALUE) {
            return \true;
        }
        return \false;
    }
    /**
     * @param array $fields
     * @param array $args
     *
     * @return array
     */
    public function add_address_replacements($fields, $args)
    {
        $value = isset($args[$this->get_field_id()]) && $args[$this->get_field_id()] ? \__('yes', 'flexible-invoices') : \__('no', 'flexible-invoices');
        if ($this->is_field_checked_from_args($args)) {
            $fields['{' . $this->get_field_id() . '}'] = $this->label . ': ' . $value;
        } else {
            $fields['{' . $this->get_field_id() . '}'] = '';
        }
        return $fields;
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
        return ['label' => $this->label, 'required' => $this->get_required(), 'class' => array('form-row-wide'), 'type' => 'checkbox', 'clear' => \true, 'priority' => $field_priority];
    }
    /**
     * Prepare admin field.
     *
     * @return bool|array
     */
    protected function prepare_admin_field()
    {
        $field = ['label' => $this->label, 'required' => $this->get_required(), 'class' => 'form-row-wide', 'type' => 'select', 'clear' => \true, 'options' => array(self::CHECKBOX_UNCHECKED_VALUE => \__('no', 'flexible-invoices'), self::CHECKBOX_CHECKED_VALUE => \__('yes', 'flexible-invoices')), 'show' => \false];
        return $field;
    }
}
