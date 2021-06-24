<?php

namespace WPDeskFIVendor\WPDesk\Forms\Serializer;

use WPDeskFIVendor\WPDesk\Forms\Serializer;
class NoSerialize implements \WPDeskFIVendor\WPDesk\Forms\Serializer
{
    public function serialize($value)
    {
        return $value;
    }
    public function unserialize($value)
    {
        return $value;
    }
}
