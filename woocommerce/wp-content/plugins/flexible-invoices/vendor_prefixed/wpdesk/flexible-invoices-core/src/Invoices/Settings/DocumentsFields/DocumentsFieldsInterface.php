<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\DocumentsFields;

use WPDeskFIVendor\WPDesk\Forms\Field;
/**
 * Documents settings fields interfaces.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings
 */
interface DocumentsFieldsInterface
{
    /**
     * @return Field[]
     */
    public function get_fields();
    /**
     * @return string
     */
    public static function get_tab_slug();
}
