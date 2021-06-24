<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration;

use Psr\Log\LoggerInterface;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
/**
 * Class that grants access to some internal classes and info about Flexible Invoice to external plugins.
 *
 * @package WPDesk\ShopMagic\Integration
 */
class ExternalPluginsAccess
{
    /**
     * @var string
     */
    private $version;
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @var SaveDocument
     */
    private $document_saver;
    /**
     * @var SettingsStrategy
     */
    private $settings_strategy;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var LibraryInfo
     */
    private $library_info;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var PDF
     */
    private $pdf;
    /**
     * @param string           $version
     * @param DocumentFactory  $document_factory
     * @param SaveDocument     $document_saver
     * @param SettingsStrategy $settings_strategy
     * @param Settings         $settings
     * @param LibraryInfo      $library_info
     * @param LoggerInterface  $logger
     * @param Renderer         $renderer
     * @param PDF              $pdf
     */
    public function __construct(string $version, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\SaveDocument $document_saver, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $settings_strategy, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo $library_info, \Psr\Log\LoggerInterface $logger, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF $pdf)
    {
        $this->version = $version;
        $this->document_factory = $document_factory;
        $this->document_saver = $document_saver;
        $this->settings_strategy = $settings_strategy;
        $this->settings = $settings;
        $this->library_info = $library_info;
        $this->logger = $logger;
        $this->renderer = $renderer;
        $this->pdf = $pdf;
    }
    /**
     * @return string
     */
    public function get_version()
    {
        return $this->version;
    }
    /**
     * @return DocumentFactory
     */
    public function get_document_factory()
    {
        return $this->document_factory;
    }
    /**
     * @return SaveDocument
     */
    public function get_document_saver()
    {
        return $this->document_saver;
    }
    /**
     * @return SettingsStrategy
     */
    public function get_settings_strategy()
    {
        return $this->settings_strategy;
    }
    /**
     * @return Settings
     */
    public function get_settings()
    {
        return $this->settings;
    }
    /**
     * @return LibraryInfo
     */
    public function get_library_info()
    {
        return $this->library_info;
    }
    /**
     * @return LoggerInterface
     */
    public function get_logger()
    {
        return $this->logger;
    }
    /**
     * @return Renderer
     */
    public function get_renderer()
    {
        return $this->renderer;
    }
    /**
     * @return WordPress\PDF
     */
    public function get_pdf()
    {
        return $this->pdf;
    }
}
