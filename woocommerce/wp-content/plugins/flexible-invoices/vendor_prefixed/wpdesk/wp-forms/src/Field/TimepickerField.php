<?php

namespace WPDeskFIVendor\WPDesk\Forms\Field;

class TimepickerField extends \WPDeskFIVendor\WPDesk\Forms\Field\BasicField
{
    /**
     * @inheritDoc
     */
    public function get_template_name()
    {
        return 'timepicker';
    }
}
