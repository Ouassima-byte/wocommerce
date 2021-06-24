<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields;

use WPDeskFIVendor\WPDesk\Forms\Field\BasicField;
class TableGroupedFields extends \WPDeskFIVendor\WPDesk\Forms\Field\BasicField
{
    private $empty_values = [];
    public function __construct()
    {
        parent::__construct();
        $this->attributes['multiple'] = \true;
        $this->set_attribute('type', 'text');
    }
    /**
     * @param array $items
     *
     * @return $this
     */
    public function set_items(array $items)
    {
        $this->meta['items'] = $items;
        return $this;
    }
    public function set_empty_values(array $values)
    {
        $this->empty_values = $values;
        return $this;
    }
    public function get_empty_values()
    {
        return $this->empty_values;
    }
    /**
     * @return array
     */
    public function get_items()
    {
        return isset($this->meta['items']) && \is_array($this->meta['items']) ? $this->meta['items'] : array();
    }
    /**
     * @return string
     */
    public function get_template_name()
    {
        return 'table-grouped-fields';
    }
}
