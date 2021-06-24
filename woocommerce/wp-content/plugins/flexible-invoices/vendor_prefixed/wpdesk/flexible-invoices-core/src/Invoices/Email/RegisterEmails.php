<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Register email classes for WooCommerce.
 *
 * @package WPDesk\WooCommerceFakturownia\Email
 */
class RegisterEmails implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @param DocumentFactory $document_factory
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory)
    {
        $this->document_factory = $document_factory;
    }
    /**
     * Hooks
     */
    public function hooks()
    {
        \add_filter('woocommerce_email_classes', [$this, 'register_emails'], 11);
    }
    /**
     * Register emails in WooCommerce.
     *
     * @param array $emails Emails.
     *
     * @return array
     */
    public function register_emails(array $emails)
    {
        foreach ($this->document_factory->get_creators() as $creator) {
            $emails['fi_' . $creator->get_type()] = $creator->get_email_class();
        }
        return $emails;
    }
}
