<?php

namespace WPDeskFIVendor\WPDesk\View\Resolver;

use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
use WPDeskFIVendor\WPDesk\View\Resolver\Exception\CanNotResolve;
/**
 * This resolver never finds the file
 *
 * @package WPDesk\View\Resolver
 */
class NullResolver implements \WPDeskFIVendor\WPDesk\View\Resolver\Resolver
{
    public function resolve($name, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer = null)
    {
        throw new \WPDeskFIVendor\WPDesk\View\Resolver\Exception\CanNotResolve("Null Cannot resolve");
    }
}
