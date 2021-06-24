<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Creator;

/**
 * Document creator restrictions.
 *
 * Restrictions are needed mainly for issuing documents through integration with WooCommerce.
 */
interface DocumentCreatorRestrictions
{
    /**
     * Is it allowed to edit in the WordPress panel.
     *
     * @return bool
     */
    public function is_allowed_for_edit();
    /**
     * @return bool
     */
    public function is_allowed_for_create();
    /**
     * @return bool
     */
    public function is_allowed_for_auto_create();
    /**
     * @return bool
     */
    public function is_allowed_to_send();
    /**
     * @return bool
     */
    public function can_show_document_in_my_account();
}
