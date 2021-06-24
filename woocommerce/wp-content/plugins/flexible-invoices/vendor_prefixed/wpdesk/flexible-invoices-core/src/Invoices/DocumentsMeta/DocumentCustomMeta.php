<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\DocumentsMeta;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Containers\MetaContainer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\DocumentGetters;
/**
 * Abstraction for retrieving custom meta for documents.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Strategy
 */
abstract class DocumentCustomMeta implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\DocumentsMeta\CustomMeta
{
    /**
     * @var DocumentGetters
     */
    protected $document;
    /**
     * @var MetaContainer
     */
    protected $meta_container;
    /**
     * @param DocumentGetters $document
     * @param MetaContainer   $meta_container
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\DocumentGetters $document, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Containers\MetaContainer $meta_container)
    {
        $this->document = $document;
        $this->meta_container = $meta_container;
    }
    /**
     * @return void
     */
    public abstract function save();
}
