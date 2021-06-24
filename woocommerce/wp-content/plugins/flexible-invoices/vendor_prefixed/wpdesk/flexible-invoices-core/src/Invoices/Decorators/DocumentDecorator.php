<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
/**
 * Decorates document for editing && pdf.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Decorators
 */
class DocumentDecorator extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\BaseDecorator implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document
{
    /**
     * @param string $value
     */
    public function set_date_of_paid($value)
    {
        $this->document->set_date_of_paid($value);
    }
    /**
     * @return string
     */
    public function get_date_of_paid()
    {
        return \date('Y-m-d', $this->document->get_date_of_paid());
    }
    /**
     * @param string $value
     */
    public function set_date_of_issue($value)
    {
        $this->document->set_date_of_issue($value);
    }
    /**
     * @return string
     */
    public function get_date_of_issue()
    {
        return \date('Y-m-d', $this->document->get_date_of_issue());
    }
    /**
     * @param string $value
     */
    public function set_date_of_sale($value)
    {
        $this->document->set_date_of_sale($value);
    }
    /**
     * @return string
     */
    public function get_date_of_sale()
    {
        return \date('Y-m-d', $this->document->get_date_of_sale());
    }
    /**
     * @param string $value
     */
    public function set_date_of_pay($value)
    {
        $this->document->set_date_of_pay($value);
    }
    /**
     * @return string
     */
    public function get_date_of_pay()
    {
        return \date('Y-m-d', $this->document->get_date_of_pay());
    }
    /**
     * @return string
     */
    public function get_payment_status_name()
    {
        foreach ($this->strategy->get_payment_statuses() as $method_key => $method_name) {
            if ($method_key === $this->document->get_payment_status()) {
                return $method_name;
            }
        }
        return $this->document->get_payment_status();
    }
    /**
     * @return float
     */
    public function get_total_tax()
    {
        return $this->currency_helper->number_format($this->document->get_total_tax());
    }
    /**
     * @return float|int
     */
    public function get_total_net()
    {
        return $this->currency_helper->number_format($this->document->get_total_net());
    }
    /**
     * @param float $value
     */
    public function set_total_net($value)
    {
        $this->document->set_total_net($value);
    }
    /**
     * @return float
     */
    public function get_total_gross()
    {
        return $this->currency_helper->number_format($this->document->get_total_gross());
    }
}
