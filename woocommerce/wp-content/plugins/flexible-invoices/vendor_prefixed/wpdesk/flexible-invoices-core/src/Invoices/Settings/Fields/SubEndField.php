<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields;

use WPDeskFIVendor\WPDesk\Forms\Field\Header;
/**
 * Field to close table.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\Fields
 */
class SubEndField extends \WPDeskFIVendor\WPDesk\Forms\Field\Header
{
    public function __construct()
    {
        parent::__construct();
        $this->set_default_value('');
        $this->set_attribute('type', 'text');
    }
    /**
     * @return string
     */
    public function get_template_name()
    {
        return 'sub-end';
    }
}
