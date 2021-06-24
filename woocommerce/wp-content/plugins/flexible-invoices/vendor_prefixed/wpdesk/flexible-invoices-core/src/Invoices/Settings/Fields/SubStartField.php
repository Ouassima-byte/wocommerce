<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields;

use WPDeskFIVendor\WPDesk\Forms\Field\Header;
/**
 * Field for open table.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\Fields
 */
class SubStartField extends \WPDeskFIVendor\WPDesk\Forms\Field\Header
{
    public function __construct()
    {
        parent::__construct();
        $this->set_default_value('');
        $this->set_attribute('type', 'text');
    }
    public function get_name()
    {
        return $this->attributes['name'];
    }
    /**
     * @return string
     */
    public function get_template_name()
    {
        return 'sub-start';
    }
}
