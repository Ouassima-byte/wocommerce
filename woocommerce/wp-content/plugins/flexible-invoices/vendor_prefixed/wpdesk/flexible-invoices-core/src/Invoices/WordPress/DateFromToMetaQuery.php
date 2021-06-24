<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

/**
 * Abstraction for a date query implementation for reports and download.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WordPress
 */
abstract class DateFromToMetaQuery
{
    /**
     * @param string $date
     *
     * @return bool
     */
    protected function validateDate($date) : bool
    {
        if (!$date) {
            return \false;
        }
        $month = (int) \date('m', \strtotime($date));
        $day = (int) \date('d', \strtotime($date));
        $year = (int) \date('Y', \strtotime($date));
        return \checkdate($month, $day, $year);
    }
    /**
     * @param string $date
     *
     * @return int
     */
    protected function get_start_date($date) : int
    {
        if ($this->validateDate($date)) {
            return \strtotime($date . ' 00:00:00');
        }
        return \strtotime(\date('Y-m-d 00:00:00', \strtotime("-30 days")));
    }
    /**
     * @param string $date
     *
     * @return int
     */
    protected function get_end_date($date) : int
    {
        if ($this->validateDate($date)) {
            return \strtotime($date . ' 23:59:59');
        }
        return \strtotime(\date('Y-m-d 23:59:59'));
    }
    /**
     * @param array $post_data
     *
     * @return array
     */
    protected function get_meta_query($post_data) : array
    {
        $filter_date_from = $this->get_start_date($post_data['start_date']);
        $filter_date_to = $this->get_end_date($post_data['end_date']);
        if ($filter_date_from && !$filter_date_to) {
            $meta_value = $filter_date_from;
            $meta_compare = '>=';
        } elseif (!$filter_date_from && $filter_date_to) {
            $meta_value = $filter_date_to;
            $meta_compare = '<=';
        } else {
            $meta_value = [$filter_date_from, $filter_date_to];
            $meta_compare = 'BETWEEN';
        }
        return ['key' => '_date_issue', 'value' => $meta_value, 'compare' => $meta_compare];
    }
}
