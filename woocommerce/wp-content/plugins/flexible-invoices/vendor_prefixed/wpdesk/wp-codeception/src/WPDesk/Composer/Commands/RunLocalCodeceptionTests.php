<?php

namespace WPDeskFIVendor\WPDesk\Composer\Codeception\Commands;

use WPDeskFIVendor\Symfony\Component\Console\Input\InputArgument;
use WPDeskFIVendor\Symfony\Component\Console\Input\InputInterface;
use WPDeskFIVendor\Symfony\Component\Console\Output\OutputInterface;
/**
 * Codeception tests run command.
 *
 * @package WPDesk\Composer\Codeception\Commands
 */
class RunLocalCodeceptionTests extends \WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\RunCodeceptionTests
{
    /**
     * Configure command.
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('run-local-codeception-tests')->setDescription('Run local codeception tests.')->setDefinition(array(new \WPDeskFIVendor\Symfony\Component\Console\Input\InputArgument(self::SINGLE, \WPDeskFIVendor\Symfony\Component\Console\Input\InputArgument::OPTIONAL, 'Name of Single test to run.', ' '), new \WPDeskFIVendor\Symfony\Component\Console\Input\InputArgument(self::WOOCOMMERCE_VERSION, \WPDeskFIVendor\Symfony\Component\Console\Input\InputArgument::OPTIONAL, 'WooCommerce version to install.', '')));
    }
    /**
     * Execute command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(\WPDeskFIVendor\Symfony\Component\Console\Input\InputInterface $input, \WPDeskFIVendor\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $singleTest = $input->getArgument(self::SINGLE);
        $wooVersion = $input->getArgument(self::WOOCOMMERCE_VERSION);
        $runLocalTests = 'sh ./vendor/wpdesk/wp-codeception/scripts/run_local_tests.sh ' . $singleTest . ' ' . $wooVersion;
        $this->execAndOutput($runLocalTests, $output);
    }
}
