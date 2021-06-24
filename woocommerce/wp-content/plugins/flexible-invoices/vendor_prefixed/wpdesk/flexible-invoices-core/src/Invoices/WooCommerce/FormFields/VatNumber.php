<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields;

/**
 * Define vat number field.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields
 */
class VatNumber extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields\FormField
{
    /**
     * @var string
     */
    protected $label;
    /**
     * @var string
     */
    protected $placeholder;
    /**
     * @param string $field_id    Field ID.
     * @param string $label       Label.
     * @param string $placeholder Placeholder.
     */
    public function __construct($field_id, $label, $placeholder)
    {
        parent::__construct($field_id);
        $this->label = $label;
        $this->placeholder = $placeholder;
    }
    /**
     * @param array $fields
     * @param array $args
     *
     * @return array
     */
    public function add_address_replacements($fields, $args)
    {
        if (!empty($args[$this->get_field_id()])) {
            $fields['{' . $this->get_field_id() . '}'] = $this->label . ': ' . $args[$this->get_field_id()];
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
        return ['label' => $this->label, 'placeholder' => $this->placeholder, 'required' => $this->get_required(), 'class' => \is_admin() ? '' : array('form-row-wide'), 'clear' => \true, 'priority' => $field_priority];
    }
    /**
     * Prepare admin field.
     *
     * @return bool|array
     */
    protected function prepare_admin_field()
    {
        $field = $this->prepare_checkout_field();
        $field['show'] = \false;
        return $field;
    }
}
