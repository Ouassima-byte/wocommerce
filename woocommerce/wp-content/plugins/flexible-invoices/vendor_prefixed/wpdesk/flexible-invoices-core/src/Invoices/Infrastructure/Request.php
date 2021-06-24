<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Infrastructure;

/**
 * Parse requests
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Infrastructure
 */
class Request
{
    /**
     * @var array
     */
    private $parameters;
    public function __construct()
    {
        $this->parameters = array('METHOD' => \strtoupper(isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET'), 'GET' => $_GET, 'POST' => $_POST, 'FILES' => $_FILES, 'COOKIE' => $_COOKIE, 'SERVER' => $_SERVER, 'SESSION' => isset($_SESSION) ? $_SESSION : array(), 'INPUT' => \file_get_contents("php://input"));
    }
    /**
     * @param $param
     *
     * @return \WPDesk\Library\FlexibleInvoicesCore\Infrastructure\DataType
     */
    public function param($param)
    {
        return $this->get_param_from_array($param);
    }
    /**
     * @param $param
     *
     * @return bool
     */
    public function param_exists($param)
    {
        return $this->get_param_from_array($param)->has();
    }
    /**
     * @param string $param
     *
     * @return DataType
     */
    private function get_param_from_array($param)
    {
        $keys = \explode('.', $param);
        $parameters = $this->parameters;
        foreach ($keys as $key) {
            $parameters = \array_change_key_case($parameters, \CASE_UPPER);
            $key = \strtoupper($key);
            if (isset($parameters[$key])) {
                $parameters = $parameters[$key];
            } else {
                $parameters = null;
                break;
            }
        }
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Infrastructure\DataType($parameters);
    }
}
