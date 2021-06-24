<?php

namespace WPDeskFIVendor\WPDesk\Forms\Validator;

use WPDeskFIVendor\WPDesk\Forms\Validator;
class RequiredValidator implements \WPDeskFIVendor\WPDesk\Forms\Validator
{
    public function is_valid($value)
    {
        return $value !== null;
    }
    public function get_messages()
    {
        return [];
    }
}
