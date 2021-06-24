<?php

namespace WPDeskFIVendor\WPDesk\Forms\Sanitizer;

use WPDeskFIVendor\WPDesk\Forms\Sanitizer;
class TextFieldSanitizer implements \WPDeskFIVendor\WPDesk\Forms\Sanitizer
{
    public function sanitize($value)
    {
        return \sanitize_text_field($value);
    }
}
