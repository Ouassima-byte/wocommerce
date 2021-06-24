<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Creators;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Containers\MetaContainer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Creator\DocumentCreator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Creator\DocumentCreatorRestrictions;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\DocumentGetters;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\DocumentsMeta\CustomMeta;
/**
 * Document creator.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Creators
 */
interface Creator extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Creator\DocumentCreator, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Creator\DocumentCreatorRestrictions
{
    /**
     * Returns class for saving additional custom meta for each documents.
     *
     * @param DocumentGetters $document
     * @param MetaContainer   $meta
     *
     * @return CustomMeta
     */
    public function custom_meta(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\DocumentGetters $document, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Containers\MetaContainer $meta);
}
