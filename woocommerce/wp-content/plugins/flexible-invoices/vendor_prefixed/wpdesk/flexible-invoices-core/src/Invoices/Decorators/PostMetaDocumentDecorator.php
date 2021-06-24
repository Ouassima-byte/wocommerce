<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
/**
 * Decorates document for saving into post meta.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Decorators
 */
class PostMetaDocumentDecorator extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\BaseDecorator implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document
{
    /**
     * @return array
     */
    public function get_seller() : array
    {
        $seller = $this->document->get_seller();
        return ['name' => $seller->get_name(), 'address' => $seller->get_address(), 'nip' => $seller->get_vat_number(), 'account' => $seller->get_bank_account_number(), 'bank' => $seller->get_bank_name(), 'logo' => $seller->get_logo(), 'id' => $seller->get_id(), 'signature_user' => $seller->get_signature_user()];
    }
    /**
     * @return array
     */
    public function get_customer() : array
    {
        $customer = $this->document->get_customer();
        return ['name' => $customer->get_name(), 'street' => $customer->get_street(), 'street2' => $customer->get_street2(), 'postcode' => $customer->get_postcode(), 'city' => $customer->get_city(), 'nip' => $customer->get_vat_number(), 'country' => $customer->get_country(), 'phone' => $customer->get_phone(), 'email' => $customer->get_email(), 'type' => $customer->get_type()];
    }
}
