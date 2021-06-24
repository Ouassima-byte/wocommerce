<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Creator;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\DocumentSetters;
/**
 * An interface for creating document creators. It has methods to generate different types of documents.
 */
interface DocumentCreator
{
    /**
     * @return string
     */
    public function get_button_label();
    /**
     * @return string
     */
    public function get_name();
    /**
     * @return string
     */
    public function get_type();
    /**
     * Create document object.
     *
     * @param int    $document_id
     * @param string $source_type Source types: post, postmeta or order.
     */
    public function create_document_from_source($document_id, $source_type);
    /**
     * Get document object.
     *
     * @return Document
     */
    public function get_document();
}
