<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Infrastructure\Request;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentSeller;
/**
 * Get document data from POST.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Data
 */
class PostDocumentDataSource extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\AbstractDataSource
{
    /**
     * @var Request
     */
    public $source;
    /**
     * @var array
     */
    protected $products;
    /**
     * @var PostMetaDocumentDataSource
     */
    private $post_meta_data;
    /**
     * @param int      $post_id
     * @param Settings $options_container
     * @param string   $document_type
     */
    public function __construct($post_id, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $options_container, $document_type)
    {
        parent::__construct($options_container, $document_type);
        $this->source = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Infrastructure\Request();
        $this->post_id = $post_id;
        $this->customer = (array) $this->source->param('post.client')->get();
        $this->seller = (array) $this->source->param('post.owner')->get();
        $this->products = (array) $this->source->param('post.product')->get();
        $this->post_meta_data = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\PostMetaDocumentDataSource($post_id, $options_container, $document_type);
    }
    public function get_id()
    {
        return $this->post_id;
    }
    /**
     * @return int
     */
    public function get_date_of_sale()
    {
        return (int) \strtotime($this->source->param('post.date_sale')->get());
    }
    /**
     * @return int
     */
    public function get_date_of_pay()
    {
        return \strtotime($this->source->param('post.date_pay')->get());
    }
    /**
     * @return int
     */
    public function get_date_of_paid()
    {
        return \strtotime($this->source->param('post.date_paid')->get());
    }
    /**
     * @return int
     */
    public function get_date_of_issue()
    {
        return \strtotime($this->source->param('post.date_issue')->get());
    }
    /**
     * @return string
     */
    public function get_customer_filter_field()
    {
        return $this->get_customer()->get_name();
    }
    /**
     * @return string
     */
    public function get_currency()
    {
        return $this->source->param('post.currency')->get();
    }
    /**
     * @return float
     */
    public function get_discount()
    {
        return (float) $this->source->param('post.discount')->get();
    }
    /**
     * @return int
     */
    public function get_order_id()
    {
        if (empty($this->source->param('post.wc_order_id')->get())) {
            return $this->post_meta_data->get_order_id();
        }
        return $this->source->param('post.wc_order_id')->get();
    }
    /**
     * @return array
     */
    public function get_items()
    {
        $products = [];
        if (\count($this->products) > 0) {
            foreach ($this->products['name'] as $index => $name) {
                $vatType = \explode('|', $this->products['vat_type'][$index]);
                $qty = isset($this->products['quantity'][$index]) ? $this->products['quantity'][$index] : 1;
                if (empty($qty)) {
                    $qty = 1;
                }
                $products[] = array('name' => $name, 'sku' => $this->products['sku'][$index], 'unit' => $this->products['unit'][$index], 'quantity' => $this->format_decimal($qty), 'net_price' => $this->format_decimal($this->products['net_price'][$index]), 'discount' => 0, 'net_price_sum' => $this->format_decimal($this->products['net_price_sum'][$index]), 'vat_type' => isset($vatType[1]) ? $vatType[1] : '0', 'vat_type_index' => isset($vatType[0]) ? $vatType[0] : '0', 'vat_type_name' => isset($vatType[2]) ? $vatType[2] : '0', 'vat_rate' => $this->format_decimal($this->products['vat_sum'][$index]) / $this->format_decimal($qty), 'vat_sum' => $this->format_decimal($this->products['vat_sum'][$index]), 'total_price' => $this->format_decimal($this->products['total_price'][$index]));
            }
        }
        return $products;
    }
    /**
     * @return string
     */
    public function get_payment_method()
    {
        return $this->source->param('post.payment_method')->get();
    }
    /**
     * @return string
     */
    public function get_payment_method_name()
    {
        return $this->source->param('post.payment_method_name')->get();
    }
    /**
     * @return string
     */
    public function get_notes()
    {
        $notes = $this->source->param('post.notes')->get();
        if (!empty($notes)) {
            return \__($this->source->param('post.notes')->get(), 'flexible-invoices');
        }
        return \__($this->settings->get($this->get_document_type() . '_notes'), 'flexible-invoices');
    }
    /**
     * @return float
     */
    public function get_total_gross()
    {
        return $this->source->param('post.total_price')->get();
    }
    /**
     * @return float
     */
    public function get_total_net()
    {
        return (float) $this->calculate_total_net();
    }
    /**
     * @return float
     */
    public function get_total_paid()
    {
        return (float) $this->source->param('post.total_paid')->get();
    }
    /**
     * @return float
     */
    public function get_total_tax()
    {
        return $this->calculate_total_tax();
    }
    /**
     * @return mixed|string
     */
    public function get_user_lang()
    {
        if (empty($this->source->param('post.wpml_user_lang')->get())) {
            return $this->post_meta_data->get_user_lang();
        }
        return $this->source->param('post.wpml_user_lang')->get();
    }
    /**
     * @return string
     */
    public function get_payment_status()
    {
        return $this->source->param('post.payment_status')->get();
    }
    /**
     * @return Seller
     */
    public function get_seller()
    {
        $name = isset($this->seller['name']) ? $this->seller['name'] : '';
        $address = isset($this->seller['address']) ? $this->seller['address'] : '';
        $nip = isset($this->seller['nip']) ? $this->seller['nip'] : '';
        $bank_name = isset($this->seller['bank']) ? $this->seller['bank'] : '';
        $bank_account = isset($this->seller['account']) ? $this->seller['account'] : '';
        $logo = isset($this->seller['logo']) ? $this->seller['logo'] : '';
        $signature_user = isset($this->seller['signature_user']) ? $this->seller['signature_user'] : '';
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentSeller(0, $logo, $name, $address, $nip, $bank_name, $bank_account, $signature_user);
    }
    /**
     * @return float
     */
    private function calculate_total_tax()
    {
        $items_vats = (array) $this->source->param('post.product.vat_sum')->get();
        $vat_items = [];
        foreach ($items_vats as $vat) {
            $vat_items[]['vat_sum'] = $vat;
        }
        $total = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals::calculate_total_vat($vat_items);
        if ($total > 0) {
            return (float) $total;
        }
        return $this->total_tax;
    }
    /**
     * @return float
     */
    private function calculate_total_net()
    {
        $items_nets = (array) $this->source->param('post.product.net_price_sum')->get();
        $items_net = [];
        foreach ($items_nets as $vat) {
            $items_net[]['net_price_sum'] = $vat;
        }
        $total = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals::calculate_total_net($items_net);
        if ($total > 0) {
            return (float) $total;
        }
        return $this->total_tax;
    }
    /**
     * @param $number
     *
     * @return array|float|string
     */
    public function format_decimal($number)
    {
        $decimals = array(',');
        if (!\is_float($number)) {
            $number = \sanitize_text_field(\str_replace($decimals, '.', $number));
        }
        return $number;
    }
    /**
     * @return int
     */
    public function get_show_order_number()
    {
        $show_id = (int) $this->source->param('post.add_order_id')->get();
        return $show_id;
    }
}
