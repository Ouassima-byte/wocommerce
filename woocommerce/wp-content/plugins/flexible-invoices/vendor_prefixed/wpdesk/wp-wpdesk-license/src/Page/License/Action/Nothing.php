<?php

namespace WPDeskFIVendor\WPDesk\License\Page\License\Action;

use WPDeskFIVendor\WPDesk\License\Page\Action;
/**
 * Do nothing.
 *
 * @package WPDesk\License\Page\License\Action
 */
class Nothing implements \WPDeskFIVendor\WPDesk\License\Page\Action
{
    public function execute(array $plugin)
    {
        // NOOP
    }
}
