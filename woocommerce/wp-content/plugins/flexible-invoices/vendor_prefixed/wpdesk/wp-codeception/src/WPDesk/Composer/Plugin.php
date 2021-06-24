<?php

namespace WPDeskFIVendor\WPDesk\Composer\Codeception;

use WPDeskFIVendor\Composer\Composer;
use WPDeskFIVendor\Composer\IO\IOInterface;
use WPDeskFIVendor\Composer\Plugin\Capable;
use WPDeskFIVendor\Composer\Plugin\PluginInterface;
/**
 * Composer plugin.
 *
 * @package WPDesk\Composer\Codeception
 */
class Plugin implements \WPDeskFIVendor\Composer\Plugin\PluginInterface, \WPDeskFIVendor\Composer\Plugin\Capable
{
    /**
     * @var Composer
     */
    private $composer;
    /**
     * @var IOInterface
     */
    private $io;
    public function activate(\WPDeskFIVendor\Composer\Composer $composer, \WPDeskFIVendor\Composer\IO\IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }
    /**
     * @inheritDoc
     */
    public function deactivate(\WPDeskFIVendor\Composer\Composer $composer, \WPDeskFIVendor\Composer\IO\IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }
    /**
     * @inheritDoc
     */
    public function uninstall(\WPDeskFIVendor\Composer\Composer $composer, \WPDeskFIVendor\Composer\IO\IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }
    public function getCapabilities()
    {
        return [\WPDeskFIVendor\Composer\Plugin\Capability\CommandProvider::class => \WPDeskFIVendor\WPDesk\Composer\Codeception\CommandProvider::class];
    }
}
