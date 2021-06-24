<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration;

/**
 * Get document type form post meta.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class DocumentMetaType
{
    const CORRECTION_TYPE = 'correction';
    /**
     * @var array
     */
    private $creators;
    /**
     * @param array $creators
     */
    public function __construct($creators)
    {
        $this->creators = $creators;
    }
    /**
     * @param int    $post_id
     * @param string $default
     *
     * @return string
     */
    public function get_document_type($post_id, $default)
    {
        $type = \get_post_meta($post_id, '_type', \true);
        if ($this->is_correction($post_id)) {
            return self::CORRECTION_TYPE;
        }
        if (!$type || !isset($this->creators[$type])) {
            return $default;
        }
        return $type;
    }
    /**
     * @param int $post_id
     *
     * @return bool
     */
    private function is_correction($post_id)
    {
        $is_correction = (int) \get_post_meta($post_id, '_correction', \true);
        return $is_correction === 1;
    }
}
