<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents;

/**
 * Define Invoice Document.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Documents
 */
class Invoice extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\AbstractDocument
{
    const DOCUMENT_TYPE = 'invoice';
    const META_GENERATED = '_invoice_generated';
    /**
     * @return string
     */
    public function get_type()
    {
        return self::DOCUMENT_TYPE;
    }
}
