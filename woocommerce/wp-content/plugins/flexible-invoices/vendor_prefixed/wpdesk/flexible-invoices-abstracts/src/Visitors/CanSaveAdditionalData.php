<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Visitors;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
/**
 * Interface for declaring the classes that will be responsible for saving additional data.
 *
 */
interface CanSaveAdditionalData
{
    /**
     * @param int      $document_id
     * @param Document $document
     *
     * @return void
     */
    public function save_document($document_id, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document);
}
