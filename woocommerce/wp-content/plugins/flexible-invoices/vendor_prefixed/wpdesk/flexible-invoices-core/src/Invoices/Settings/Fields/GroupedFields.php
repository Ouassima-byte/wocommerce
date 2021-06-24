<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields;

use WPDeskFIVendor\WPDesk\Forms\Field;
class GroupedFields extends \WPDeskFIVendor\WPDesk\Forms\Field\BasicField
{
    /**
     * @var Field[]
     */
    private $grouped_fields;
    public function __construct()
    {
        parent::__construct();
        $this->set_default_value('');
        $this->set_attribute('type', 'text');
    }
    /**
     * @param array $fields
     *
     * @return GroupedFields
     */
    public function set_grouped_fields(array $fields)
    {
        $this->grouped_fields = $fields;
        return $this;
    }
    /**
     * @return Field[]
     */
    public function get_grouped_fields()
    {
        return $this->grouped_fields;
    }
    /**
     * @return string
     */
    public function get_template_name()
    {
        return 'grouped-fields';
    }
}
