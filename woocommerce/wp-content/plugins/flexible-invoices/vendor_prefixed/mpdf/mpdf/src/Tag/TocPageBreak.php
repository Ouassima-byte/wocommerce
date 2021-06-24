<?php

namespace WPDeskFIVendor\Mpdf\Tag;

class TocPageBreak extends \WPDeskFIVendor\Mpdf\Tag\FormFeed
{
    public function open($attr, &$ahtml, &$ihtml)
    {
        list($isbreak, $toc_id) = $this->tableOfContents->openTagTOCPAGEBREAK($attr);
        $this->toc_id = $toc_id;
        if ($isbreak) {
            return;
        }
        parent::open($attr, $ahtml, $ihtml);
    }
}
