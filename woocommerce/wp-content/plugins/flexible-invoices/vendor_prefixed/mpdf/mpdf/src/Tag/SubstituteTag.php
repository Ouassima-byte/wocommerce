<?php

namespace WPDeskFIVendor\Mpdf\Tag;

abstract class SubstituteTag extends \WPDeskFIVendor\Mpdf\Tag\Tag
{
    public function close(&$ahtml, &$ihtml)
    {
        $tag = $this->getTagName();
        if ($this->mpdf->InlineProperties[$tag]) {
            $this->mpdf->restoreInlineProperties($this->mpdf->InlineProperties[$tag]);
        }
        unset($this->mpdf->InlineProperties[$tag]);
        $ltag = \strtolower($tag);
        $this->mpdf->{$ltag} = \false;
    }
}
