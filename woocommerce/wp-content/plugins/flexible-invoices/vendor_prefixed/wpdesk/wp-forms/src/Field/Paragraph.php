<?php

namespace WPDeskFIVendor\WPDesk\Forms\Field;

class Paragraph extends \WPDeskFIVendor\WPDesk\Forms\Field\NoValueField
{
    public function get_template_name()
    {
        return 'paragraph';
    }
    public function should_override_form_template()
    {
        return \true;
    }
}
