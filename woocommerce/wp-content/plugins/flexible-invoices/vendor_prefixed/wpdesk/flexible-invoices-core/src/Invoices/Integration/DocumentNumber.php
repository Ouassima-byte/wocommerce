<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration;

use Exception;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
/**
 * This class define document number for each document types.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class DocumentNumber
{
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $prefix;
    /**
     * @var string
     */
    private $suffix;
    /**
     * @var int
     */
    private $document_number = 1;
    /**
     * @var string
     */
    private $issue_date;
    /**
     * @var Document
     */
    private $document;
    /**
     * @param Settings $settings
     * @param Document $document
     * @param string   $name
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, $name = 'Document')
    {
        $this->settings = $settings;
        $this->document = $document;
        $this->type = $document->get_type();
        $this->issue_date = $document->get_date_of_issue();
        $this->prefix = $settings->get($this->type . '_number_prefix', $name);
        $this->suffix = $settings->get($this->type . '_number_suffix', '/{MM}/{YYYY}');
        $this->init_document_number();
    }
    /**
     * @return int
     */
    private function get_option_number() : int
    {
        global $wpdb;
        $number = (int) $wpdb->get_var("SELECT `option_value` FROM {$wpdb->options} WHERE `option_name` = 'inspire_invoices_{$this->type}_start_number' ");
        if (!$number) {
            return 1;
        }
        return $number;
    }
    /**
     * @param int $value
     */
    private function update_number($value)
    {
        global $wpdb;
        $wpdb->update($wpdb->options, array('option_value' => $value), array('option_name' => 'inspire_invoices_' . $this->type . '_start_number'));
    }
    /**
     * @return int
     */
    private function init_document_number()
    {
        $number_reset_type = $this->settings->get($this->type . '_number_reset_type', 'year');
        $number_reset_time = (int) $this->settings->get($this->type . '_start_number_timestamp', \time());
        $reset_number = \false;
        if ($number_reset_type === 'month') {
            if (\date('m.Y', $this->issue_date) !== \date('m.Y', $number_reset_time)) {
                $reset_number = \true;
            }
        }
        if ($number_reset_type === 'year') {
            if (\date('Y', $this->issue_date) !== \date('Y', $number_reset_time)) {
                $reset_number = \true;
            }
        }
        if ($reset_number) {
            $this->document_number = 1;
        } else {
            $this->document_number = $this->get_option_number();
            if (!$this->document_number) {
                $this->document_number = 1;
            }
        }
        return $this->document_number;
    }
    /**
     * @return string
     */
    public function get_formatted_number()
    {
        $number_array = array(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::translate_meta('inspire_invoices_' . $this->type . '_number_prefix', $this->prefix), $this->document_number, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::translate_meta('inspire_invoices_' . $this->type . '_number_suffix', $this->suffix));
        foreach ($number_array as &$value) {
            $value = \str_replace(array('{DD}', '{MM}', '{YYYY}'), array(\date('d', $this->issue_date), \date('m', $this->issue_date), \date('Y', $this->issue_date)), $value);
        }
        /**
         * Filters numbering for document.
         *
         * @param array    $number_array Array of data for numbering that will be imploded
         * @param Document $document     Document.
         *
         * @since 3.0.0
         */
        $number_array = \apply_filters('fi/core/numbering/formatted_number', $number_array, $this->document);
        return \implode('', $number_array);
    }
    /**
     * @return int
     */
    public function get_number()
    {
        return $this->document_number;
    }
    /**
     * @return void
     */
    public function increase_number()
    {
        $number = $this->document_number;
        $number++;
        $this->update_number($number);
        $this->settings->set($this->type . '_start_number_timestamp', \time());
    }
}
