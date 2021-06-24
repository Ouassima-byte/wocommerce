<?php

namespace WPDeskFIVendor\WPDesk\Forms\Field;

use WPDeskFIVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer;
class DatePickerField extends \WPDeskFIVendor\WPDesk\Forms\Field\BasicField
{
    public function __construct()
    {
        parent::__construct();
        $this->set_default_value('');
        $this->add_class('date-picker');
        $this->set_placeholder('YYYY-MM-DD');
        $this->set_attribute('type', 'text');
    }
    public function get_sanitizer()
    {
        return new \WPDeskFIVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer();
    }
    public function get_template_name()
    {
        return 'input-date-picker';
    }
}
