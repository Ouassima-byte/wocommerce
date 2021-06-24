<?php

namespace WPDeskFIVendor\WPDesk\Forms\Validator;

use WPDeskFIVendor\WPDesk\Forms\Validator;
class NoValidateValidator implements \WPDeskFIVendor\WPDesk\Forms\Validator
{
    public function is_valid($value)
    {
        return \true;
    }
    public function get_messages()
    {
        return [];
    }
}
