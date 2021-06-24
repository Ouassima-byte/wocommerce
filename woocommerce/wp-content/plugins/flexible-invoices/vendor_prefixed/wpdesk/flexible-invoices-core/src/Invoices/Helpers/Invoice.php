<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
/**
 * Invoice helpers functions.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Helpers
 */
class Invoice
{
    /**
     * @param Document $document
     *
     * @return string
     */
    public static function document_hash(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document)
    {
        $hash = \get_post_meta($document->get_id(), '_download_hash', \true);
        if (!$hash) {
            $hash = \uniqid();
            \update_post_meta($document->get_id(), '_download_hash', $hash);
        }
        return $hash;
    }
    /**
     * @param Document $document
     *
     * @return string
     */
    public static function generate_download_url(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document)
    {
        $hash = self::document_hash($document);
        return \admin_url('admin-ajax.php?action=fiw_get_document&id=' . $document->get_id() . '&hash=' . $hash . '&save_file=1');
    }
}
