<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Creators;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\DocumentsMeta\CustomMeta;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\DocumentsMeta\NullCustomMeta;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Containers\MetaContainer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentNumber;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\DocumentGetters;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\DocumentSetters;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
/**
 * Abstract document creator.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Creators
 */
abstract class AbstractDocumentCreator implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Creators\Creator
{
    const TYPE = 'invoice';
    /**
     * @var string
     */
    protected $button_label;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var Document
     */
    protected $document;
    /**
     * @var DataSourceFactory
     */
    protected $source_factory;
    /**
     * @var int
     */
    protected $order_id;
    /**
     * @var string
     */
    protected $source_type;
    /**
     * @param DataSourceFactory $source_factory
     * @param string            $button_label
     * @param string            $name
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory $source_factory, $button_label, $name)
    {
        $this->button_label = $button_label;
        $this->name = $name;
        $this->source_factory = $source_factory;
    }
    /**
     * @param int $order_id
     */
    public function set_order_id($order_id)
    {
        $this->order_id = $order_id;
    }
    /**
     * @return string
     */
    public function get_type()
    {
        return self::TYPE;
    }
    /**
     * @return string
     */
    public function get_button_label()
    {
        return $this->button_label;
    }
    /**
     * @return string
     */
    public function get_name()
    {
        return $this->name;
    }
    /**
     * @return Document
     */
    public function get_document()
    {
        return $this->document;
    }
    /**
     * @return false
     */
    public function is_allowed_for_create()
    {
        return \true;
    }
    /**
     * @return bool
     */
    public function is_allowed_for_auto_create()
    {
        return \true;
    }
    /**
     * @return \WC_Email
     */
    public abstract function get_email_class();
    /**
     * @return false
     */
    public function is_allowed_to_send()
    {
        return \true;
    }
    /**
     * @return bool
     */
    public function is_allowed_for_edit()
    {
        return \true;
    }
    /**
     * @param Document $document
     *
     * @return DocumentNumber
     */
    public function get_document_numbering($document)
    {
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentNumber($this->source_factory->get_settings(), $document, $this->get_name());
    }
    /**
     * @return bool
     */
    public function can_show_document_in_my_account()
    {
        return \true;
    }
    /**
     * @param DocumentSetters $document
     * @param int             $post_id
     * @param string          $source_type
     *
     * @throws \Exception
     */
    protected function assign_data_from_source(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\DocumentSetters $document, $post_id, $source_type)
    {
        $data = $this->source_factory->get_source($post_id, $source_type, $this->get_type());
        $document->set_number($data->get_number());
        $document->set_formatted_number($data->get_formatted_number());
        $document->set_date_of_pay($data->get_date_of_pay());
        $document->set_date_of_paid($data->get_date_of_paid());
        $document->set_date_of_issue($data->get_date_of_issue());
        $document->set_date_of_sale($data->get_date_of_sale());
        $document->set_customer($data->get_customer());
        $document->set_customer_filter_field($data->get_customer_filter_field());
        $document->set_seller($data->get_seller());
        $document->set_currency($data->get_currency());
        $document->set_discount($data->get_discount());
        $document->set_id($data->get_id());
        $document->set_items($data->get_items());
        $document->set_payment_method($data->get_payment_method());
        $document->set_payment_method_name($data->get_payment_method_name());
        $document->set_payment_status($data->get_payment_status());
        $document->set_notes($data->get_notes());
        $document->set_tax($data->get_tax());
        $document->set_total_gross($data->get_total_gross());
        $document->set_total_net($data->get_total_net());
        $document->set_total_paid($data->get_total_paid());
        $document->set_total_gross($data->get_total_gross());
        $document->set_total_tax($data->get_total_tax());
        $document->set_user_lang($data->get_user_lang());
        $document->set_show_order_number($data->get_show_order_number());
        $document->set_order_id($data->get_order_id());
        $document->set_corrected_id($data->get_corrected_id());
        $this->document = $document;
    }
    /**
     * @param DocumentGetters $document
     * @param MetaContainer   $meta
     *
     * @return CustomMeta
     */
    public function custom_meta(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\DocumentGetters $document, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Containers\MetaContainer $meta)
    {
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\DocumentsMeta\NullCustomMeta($document, $meta);
    }
    /**
     * @return array
     */
    public function get_auto_create_statuses()
    {
        $settings = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings();
        $status = $settings->get($this->get_type() . '_auto_create_status', []);
        if (\is_string($status) && !empty($status)) {
            return [$status];
        } elseif (\is_array($status)) {
            return $status;
        }
        return [];
    }
}
