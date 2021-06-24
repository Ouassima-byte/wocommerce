<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields;

use WPDeskFIVendor\WPDesk\Forms\Field\BasicField;
use WPDeskFIVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer;
/**
 * Date Field.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\Fields
 */
class InputDateField extends \WPDeskFIVendor\WPDesk\Forms\Field\BasicField
{
    public function __construct()
    {
        parent::__construct();
        $this->set_default_value('');
        $this->set_attribute('type', 'date');
    }
    public function get_sanitizer()
    {
        return new \WPDeskFIVendor\WPDesk\Forms\Sanitizer\TextFieldSanitizer();
    }
    public function get_template_name()
    {
        return 'input-date';
    }
}
