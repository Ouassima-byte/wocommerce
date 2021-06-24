<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Creator\DocumentCreator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentExceptions\UnknownDocumentTypeException;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice;
/**
 * A document factory that returns document creator.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class DocumentFactory
{
    /**
     * @var DocumentCreator[]
     */
    private $creators;
    /**
     * @var string
     */
    private $document_type;
    /**
     * @var DocumentMetaType
     */
    private $meta_type;
    /**
     * @param CreatorContainer $creators_factory
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\CreatorContainer $creators_factory)
    {
        $this->creators = $creators_factory->get_creators();
        $this->meta_type = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentMetaType($this->creators);
    }
    /**
     * @return DocumentCreator[]
     */
    public function get_creators()
    {
        return $this->creators;
    }
    /**
     * @param string $type
     */
    public function set_document_type($type)
    {
        $this->document_type = $type;
    }
    /**
     * @param int    $document_id Post ID.
     * @param string $source_type Source type from document will be created.
     *
     * @return DocumentCreator
     *
     * @throws UnknownDocumentTypeException
     */
    public function get_document_creator($document_id, $source_type = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory::META_SOURCE)
    {
        foreach ($this->creators as $document_creator) {
            $creator = clone $document_creator;
            $document_type = $this->get_document_type($document_id, $document_creator->get_type());
            if (isset($this->creators[$document_type])) {
                if ($creator->get_type() === $document_type) {
                    $creator->create_document_from_source($document_id, $source_type);
                    return $creator;
                }
            }
        }
        return $this->create_default_creator($document_id, $source_type);
    }
    /**
     * @param int    $document_id
     * @param string $source_type
     *
     * @return DocumentCreator
     */
    private function create_default_creator($document_id, $source_type)
    {
        $this->creators[\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice::DOCUMENT_TYPE]->create_document_from_source($document_id, $source_type);
        return $this->creators[\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice::DOCUMENT_TYPE];
    }
    /**
     * @param int    $document_id
     * @param string $type
     *
     * @return string
     */
    public function get_document_type($document_id, $type)
    {
        if (!$this->document_type) {
            return $this->meta_type->get_document_type($document_id, $type);
        } else {
            return $this->document_type;
        }
    }
}
