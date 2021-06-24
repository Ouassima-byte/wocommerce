<?php

namespace WPDeskFIVendor\WPDesk\Forms\Serializer;

use WPDeskFIVendor\WPDesk\Forms\Serializer;
class SerializeSerializer implements \WPDeskFIVendor\WPDesk\Forms\Serializer
{
    public function serialize($value)
    {
        return \serialize($value);
    }
    public function unserialize($value)
    {
        return \unserialize($value);
    }
}
