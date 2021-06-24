<?php

namespace WPDeskFIVendor\WPDesk\PluginBuilder\Storage;

class StorageFactory
{
    /**
     * @return PluginStorage
     */
    public function create_storage()
    {
        return new \WPDeskFIVendor\WPDesk\PluginBuilder\Storage\WordpressFilterStorage();
    }
}
