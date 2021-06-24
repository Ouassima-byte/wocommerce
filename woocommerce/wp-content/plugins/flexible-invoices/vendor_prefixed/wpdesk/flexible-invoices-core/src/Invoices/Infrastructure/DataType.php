<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Infrastructure;

/**
 * Class DataType
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Infrastructure
 */
class DataType
{
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function has()
    {
        return isset($this->data);
    }
    public function is_empty()
    {
        return empty($this->data);
    }
    public function is_string()
    {
        return \is_string($this->data);
    }
    public function is_integer()
    {
        return \is_int($this->data);
    }
    public function is_float()
    {
        return \is_float($this->data);
    }
    public function is_numeric()
    {
        return \is_numeric($this->data);
    }
    public function is_array()
    {
        return \is_array($this->data);
    }
    public function is_object()
    {
        return \is_object($this->data);
    }
    public function is_url()
    {
        return $this->is_string() && \filter_var($this->data, \FILTER_VALIDATE_URL);
    }
    public function is_serialized()
    {
        return \is_serialized($this->data);
    }
    public function is_json()
    {
        \json_decode($this->data);
        return \json_last_error() == \JSON_ERROR_NONE;
    }
    public function get()
    {
        return $this->data;
    }
    public function get_as_string()
    {
        if ($this->is_string()) {
            return $this->data;
        }
        if ($this->is_numeric()) {
            return (string) $this->data;
        }
        if ($this->is_array() || $this->is_object()) {
            return $this->get_serialized();
        }
        return '';
    }
    public function get_as_json()
    {
        $data = $this->has() ? $this->data : "";
        if ($this->is_object()) {
            $data = (array) $data;
        }
        return \json_encode($data);
    }
    public function get_parsed_string_as_array()
    {
        $data = [];
        \parse_str($this->data, $data);
        return $data;
    }
    public function get_serialized()
    {
        return \serialize($this->data);
    }
    public function get_sanitized($textarea = \false)
    {
        if (!$this->has()) {
            return '';
        }
        if ($this->is_object() || $this->is_array()) {
            $map = function ($func, array $arr) {
                \array_walk_recursive($arr, function (&$v) use($func) {
                    $v = $func($v);
                });
                return $arr;
            };
            return $map($textarea ? 'sanitize_textarea_field' : 'sanitize_text_field', $this->is_object() ? (array) $this->data : $this->data);
        }
        $data = (string) $this->data;
        return \false === $textarea ? \sanitize_textarea_field($data) : \sanitize_text_field($data);
    }
    public function __toString()
    {
        return $this->get_as_string();
    }
}
