<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields;

use WPDeskFIVendor\WPDesk\Forms\Field\BasicField;
/**
 * This field class exists for backward compatibility. The old version used on & off instead of yes & no.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\Fields
 */
class FICheckboxField extends \WPDeskFIVendor\WPDesk\Forms\Field\BasicField
{
    const VALUE_TRUE = 'on';
    // Backward compatibility
    const VALUE_FALSE = 'off';
    // Backward compatibility
    public function __construct()
    {
        parent::__construct();
        $this->set_attribute('type', 'checkbox');
    }
    public function get_template_name()
    {
        return 'input-checkbox';
    }
    public function get_sublabel()
    {
        return $this->meta['sublabel'];
    }
    public function set_sublabel($value)
    {
        $this->meta['sublabel'] = $value;
        return $this;
    }
    public function has_sublabel()
    {
        return isset($this->meta['sublabel']);
    }
}
