<?php

namespace WPDeskFIVendor\WPDesk\Persistence\Decorator;

use WPDeskFIVendor\WPDesk\Persistence\ElementNotExistsException;
use WPDeskFIVendor\WPDesk\Persistence\PersistentContainer;
use WPDeskFIVendor\WPDesk\Persistence\AllDataAccessContainer;
/**
 * You can use this class to delay write access to any PersistenceContainer and save it as single value.
 *
 * @package WPDesk\Persistence
 */
final class DelaySinglePersistentContainer extends \WPDeskFIVendor\WPDesk\Persistence\Decorator\DelayPersistentContainer implements \WPDeskFIVendor\WPDesk\Persistence\AllDataAccessContainer
{
    /**
     * Key where the data will be saved.
     *
     * @var string
     */
    private $key;
    public function __construct(\WPDeskFIVendor\WPDesk\Persistence\PersistentContainer $container, $key)
    {
        parent::__construct($container);
        $this->key = $key;
    }
    public function get($id)
    {
        if (isset($this->changed[$id]) && $this->changed[$id]) {
            if (isset($this->internal_data[$id])) {
                return $this->internal_data[$id];
            }
        } else {
            $data = \unserialize($this->container->get($this->key));
            if (\is_array($data) && isset($data[$id])) {
                return $data[$id];
            }
        }
        throw new \WPDeskFIVendor\WPDesk\Persistence\ElementNotExistsException(\sprintf('Element %s not exists!', $id));
    }
    public function has($id)
    {
        if (isset($this->changed[$id]) && $this->changed[$id]) {
            return isset($this->internal_data[$id]);
        }
        if ($this->container->has($this->key)) {
            $data = \unserialize($this->container->get($this->key));
            return \is_array($data) && isset($data[$id]);
        }
        return \false;
    }
    public function save()
    {
        if ($this->is_changed()) {
            $this->container->set($this->key, \serialize($this->internal_data));
            $this->reset();
        }
    }
    /**
     * @see AllDataAccessContainer::get_all()
     */
    public function get_all()
    {
        if (!empty($this->changed)) {
            if (!empty($this->internal_data)) {
                return $this->internal_data;
            }
        } else {
            $data = \unserialize($this->container->get($this->key));
            if (!empty($data)) {
                return $data;
            }
        }
        return array();
    }
}
