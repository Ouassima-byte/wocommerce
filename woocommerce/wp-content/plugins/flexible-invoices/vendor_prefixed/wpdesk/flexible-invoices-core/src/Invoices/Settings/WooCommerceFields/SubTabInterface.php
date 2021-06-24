<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields;

use WPDeskFIVendor\WPDesk\Forms\Field;
/**
 * Interface for define tab subpages.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings
 */
interface SubTabInterface
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
