<?php

namespace WPDeskFIVendor\WPDesk\Composer\Codeception;

use WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\CreateCodeceptionTests;
use WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\RunCodeceptionTests;
use WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\RunLocalCodeceptionTests;
/**
 * Links plugin commands handlers to composer.
 */
class CommandProvider implements \WPDeskFIVendor\Composer\Plugin\Capability\CommandProvider
{
    public function getCommands()
    {
        return [new \WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\CreateCodeceptionTests(), new \WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\RunCodeceptionTests(), new \WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\RunLocalCodeceptionTests()];
    }
}
