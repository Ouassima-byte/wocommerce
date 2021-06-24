<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs;

class PDF
{
    const INVOICE_DIRECTORY_NAME = 'wordpress_invoices';
    /**
     * @return string
     */
    public static function get_pdf_path()
    {
        $upload_dir = \wp_upload_dir();
        $path = \trailingslashit($upload_dir['basedir']) . \trailingslashit(self::INVOICE_DIRECTORY_NAME);
        \wp_mkdir_p($path);
        return $path;
    }
}
