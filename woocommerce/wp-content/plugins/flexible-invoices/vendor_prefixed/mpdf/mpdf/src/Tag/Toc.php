<?php

namespace WPDeskFIVendor\Mpdf\Tag;

class Toc extends \WPDeskFIVendor\Mpdf\Tag\Tag
{
    public function open($attr, &$ahtml, &$ihtml)
    {
        //added custom-tag - set Marker for insertion later of ToC
        $this->tableOfContents->openTagTOC($attr);
    }
    public function close(&$ahtml, &$ihtml)
    {
    }
}
