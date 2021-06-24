<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields;

/**
 * Attribute tab close field.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\Fields
 */
class AttributesSubEndField extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubEndField
{
    /**
     * @return string
     */
    public function get_template_name()
    {
        return 'attributes-sub-end';
    }
}
