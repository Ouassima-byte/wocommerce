<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields;

/**
 * Currency table field.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\Fields
 */
class CurrencyFields extends \WPDeskFIVendor\WPDesk\Forms\Field\BasicField
{
    public function __construct()
    {
        parent::__construct();
        $this->set_default_value('');
        $this->set_attribute('type', 'text');
    }
    public function get_template_name()
    {
        return 'currency-fields';
    }
}
