<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentExceptions\InvalidDocumentDataException;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
/**
 * This factory define document data sources. They can be taken from postmeta, $_POST or WC_Order.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Data
 */
class DataSourceFactory
{
    const POST_SOURCE = 'post';
    const META_SOURCE = 'meta';
    const ORDER_SOURCE = 'order';
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @param Settings $settings
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings)
    {
        $this->settings = $settings;
    }
    /**
     * @return Settings
     */
    public function get_settings()
    {
        return $this->settings;
    }
    /**
     * @param int    $post_id
     * @param string $source_type
     *
     * @param string $document_type
     *
     * @return SourceData
     * @throws \Exception Unknown source.
     * @throws InvalidDocumentDataException Invalid data exception.
     */
    public function get_source($post_id, $source_type, $document_type = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice::DOCUMENT_TYPE)
    {
        try {
            switch ($source_type) {
                case self::POST_SOURCE:
                    return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\PostDocumentDataSource($post_id, $this->settings, $document_type);
                case self::META_SOURCE:
                    return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\PostMetaDocumentDataSource($post_id, $this->settings, $document_type);
                case self::ORDER_SOURCE:
                    return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\OrderDocumentDataSource($post_id, $this->settings, $document_type);
                default:
                    throw new \Exception('Unknown source');
            }
        } catch (\Exception $e) {
            throw new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentExceptions\InvalidDocumentDataException($e->getMessage());
        }
    }
}
