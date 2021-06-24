<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email;

/**
 * Invoice email class.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Email
 */
class EmailInvoice extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email\BaseEmail
{
    public function __construct()
    {
        $this->id = 'fi_invoice';
        $this->title = \__('Invoice (Flexible Invoices)', 'flexible-invoices');
        $this->description = \__('Email with invoice (Flexible Invoices).', 'flexible-invoices');
        $this->heading = \__('Email with invoice', 'flexible-invoices');
        $this->subject = \__('[{site_title}] Invoice for order #{order_number}', 'flexible-invoices');
        $this->template_html = 'emails/invoice.php';
        $this->template_plain = 'emails/plain/invoice.php';
        parent::__construct();
    }
}
