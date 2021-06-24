<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Creator\DocumentCreator;
/**
 * Register document creators.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class CreatorContainer
{
    /**
     * @var DocumentCreator[];
     */
    private $creators;
    /**
     * @param DocumentCreator $creator
     */
    public function add_creator(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Creator\DocumentCreator $creator)
    {
        $this->creators[$creator->get_type()] = $creator;
    }
    /**
     * @return DocumentCreator[]
     */
    public function get_creators()
    {
        return $this->creators;
    }
}
