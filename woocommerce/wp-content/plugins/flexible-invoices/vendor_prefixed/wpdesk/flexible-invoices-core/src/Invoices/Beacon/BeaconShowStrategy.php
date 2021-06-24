<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Beacon;

use WPDeskFIVendor\WPDesk\Beacon\BeaconShouldShowStrategy;
class BeaconShowStrategy implements \WPDeskFIVendor\WPDesk\Beacon\BeaconShouldShowStrategy
{
    const EN_LANG_CODE = 'en';
    const SETTINGS_SLUG = ['inspire_invoice', 'edit-inspire_invoice', 'inspire_invoice_page_invoices_settings', 'inspire_invoice_page_download', 'inspire_invoice_page_flexible-invoices-reports-settings'];
    public function shouldDisplay()
    {
        $screen = \get_current_screen();
        if (isset($screen->id) && \in_array($screen->id, self::SETTINGS_SLUG, \true)) {
            return \substr(\get_locale(), 0, 2) === self::EN_LANG_CODE;
        }
        return \false;
    }
}
