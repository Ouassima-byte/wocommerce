<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields;

/**
 * Image selector field.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\Fields
 */
class ImageInputField extends \WPDeskFIVendor\WPDesk\Forms\Field\BasicField
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
        return 'image-input-text';
    }
}
