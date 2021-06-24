<?php

namespace WPDeskFIVendor\Mpdf\Tag;

class Ttz extends \WPDeskFIVendor\Mpdf\Tag\SubstituteTag
{
    public function open($attr, &$ahtml, &$ihtml)
    {
        $this->mpdf->ttz = \true;
        $this->mpdf->InlineProperties['TTZ'] = $this->mpdf->saveInlineProperties();
        $this->mpdf->setCSS(['FONT-FAMILY' => 'czapfdingbats', 'FONT-WEIGHT' => 'normal', 'FONT-STYLE' => 'normal'], 'INLINE');
    }
}
