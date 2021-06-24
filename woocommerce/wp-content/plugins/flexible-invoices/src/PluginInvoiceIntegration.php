<?php

namespace WPDesk\FlexibleInvoices;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;

/**
 * Integrate with Invoices library. We can additional document creators, data source or strategy for invoicing.
 */
class PluginInvoiceIntegration extends InvoicesIntegration {

	public static $is_pro = false;

}
