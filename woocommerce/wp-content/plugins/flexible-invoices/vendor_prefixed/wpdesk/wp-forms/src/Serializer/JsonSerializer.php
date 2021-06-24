<?php

namespace WPDeskFIVendor\WPDesk\Forms\Serializer;

use WPDeskFIVendor\WPDesk\Forms\Serializer;
class JsonSerializer implements \WPDeskFIVendor\WPDesk\Forms\Serializer
{
    public function serialize($value)
    {
        return \json_encode($value);
    }
    public function unserialize($value)
    {
        return \json_decode($value, \true);
    }
}
