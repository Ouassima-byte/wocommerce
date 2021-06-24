<?php

namespace WPDeskFIVendor\WPDesk\Composer\Codeception\Commands;

use WPDeskFIVendor\Composer\Command\BaseCommand as CodeceptionBaseCommand;
use WPDeskFIVendor\Symfony\Component\Console\Output\OutputInterface;
/**
 * Base for commands - declares common methods.
 *
 * @package WPDesk\Composer\Codeception\Commands
 */
abstract class BaseCommand extends \WPDeskFIVendor\Composer\Command\BaseCommand
{
    /**
     * @param string $command
     * @param OutputInterface $output
     */
    protected function execAndOutput($command, \WPDeskFIVendor\Symfony\Component\Console\Output\OutputInterface $output)
    {
        \passthru($command);
    }
}
