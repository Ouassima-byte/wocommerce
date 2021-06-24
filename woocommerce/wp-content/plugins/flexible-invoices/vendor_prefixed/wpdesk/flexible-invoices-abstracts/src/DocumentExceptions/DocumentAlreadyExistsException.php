<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentExceptions;

/**
 * Throw exception when document already exists.
 *.
 * @package WPDesk\Library\FlexibleInvoicesAbstracts\DocumentExceptions
 */
class DocumentAlreadyExistsException extends \RuntimeException implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentExceptions\DocumentException
{
    public function __construct()
    {
        parent::__construct('Document already exists!');
    }
}
