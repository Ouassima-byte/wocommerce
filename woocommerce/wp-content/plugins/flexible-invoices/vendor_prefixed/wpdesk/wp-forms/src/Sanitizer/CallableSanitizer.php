<?php

namespace WPDeskFIVendor\WPDesk\Forms\Sanitizer;

use WPDeskFIVendor\WPDesk\Forms\Sanitizer;
class CallableSanitizer implements \WPDeskFIVendor\WPDesk\Forms\Sanitizer
{
    private $callable;
    public function __construct($callable)
    {
        $this->callable = $callable;
    }
    public function sanitize($value)
    {
        return \call_user_func($this->callable, $value);
    }
}
