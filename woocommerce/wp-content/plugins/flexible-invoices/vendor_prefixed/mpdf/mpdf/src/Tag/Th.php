<?php

namespace WPDeskFIVendor\Mpdf\Tag;

class Th extends \WPDeskFIVendor\Mpdf\Tag\Td
{
    public function close(&$ahtml, &$ihtml)
    {
        $this->mpdf->SetStyle('B', \false);
        parent::close($ahtml, $ihtml);
    }
}
