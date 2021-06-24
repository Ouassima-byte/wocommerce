<?php

namespace WPDeskFIVendor\WPDesk\Forms\Sanitizer;

use WPDeskFIVendor\WPDesk\Forms\Sanitizer;
class NoSanitize implements \WPDeskFIVendor\WPDesk\Forms\Sanitizer
{
    public function sanitize($value)
    {
        return $value;
    }
}
