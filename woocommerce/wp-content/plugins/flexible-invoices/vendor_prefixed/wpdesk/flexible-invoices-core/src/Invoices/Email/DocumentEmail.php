<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF;
/**
 * @package WPDesk\Library\FlexibleInvoicesCore\Email
 */
interface DocumentEmail
{
    /**
     * @param \WC_Order $order
     * @param Document  $document
     * @param PDF       $pdf
     *
     * @return void
     */
    public function should_send_email(\WC_Order $order, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF $pdf);
}
