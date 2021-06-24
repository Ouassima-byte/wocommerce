<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Containers;

use WPDeskFIVendor\WPDesk\Persistence\PersistentContainer;
/**
 * Meta container interface for .
 */
interface MetaContainer extends \WPDeskFIVendor\WPDesk\Persistence\PersistentContainer
{
    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get_fallback($name, $default = null);
}
