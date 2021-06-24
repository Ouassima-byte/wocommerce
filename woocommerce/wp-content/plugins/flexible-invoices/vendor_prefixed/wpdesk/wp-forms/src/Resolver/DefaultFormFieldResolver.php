<?php

namespace WPDeskFIVendor\WPDesk\Forms\Resolver;

use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
use WPDeskFIVendor\WPDesk\View\Resolver\DirResolver;
use WPDeskFIVendor\WPDesk\View\Resolver\Resolver;
/**
 * Use with View to resolver form fields to default templates.
 *
 * @package WPDesk\Forms\Resolver
 */
class DefaultFormFieldResolver implements \WPDeskFIVendor\WPDesk\View\Resolver\Resolver
{
    /** @var Resolver */
    private $dir_resolver;
    public function __construct()
    {
        $this->dir_resolver = new \WPDeskFIVendor\WPDesk\View\Resolver\DirResolver(__DIR__ . '/../../templates');
    }
    public function resolve($name, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer = null)
    {
        return $this->dir_resolver->resolve($name, $renderer);
    }
}
