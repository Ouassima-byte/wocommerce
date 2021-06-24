<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentExceptions;

/**
 * Throw an exception when the document type is not recognized.
 *
 * @package WPDesk\Library\FlexibleInvoicesAbstracts\Exceptions
 */
class UnknownDocumentTypeException extends \RuntimeException implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentExceptions\DocumentException
{
    public function __construct($document_type)
    {
        $message = \sprintf('Unknown document type %1$s!', $document_type);
        parent::__construct($message);
    }
}
