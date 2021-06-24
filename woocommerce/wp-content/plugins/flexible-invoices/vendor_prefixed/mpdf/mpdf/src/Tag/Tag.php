<?php

namespace WPDeskFIVendor\Mpdf\Tag;

use WPDeskFIVendor\Mpdf\Strict;
use WPDeskFIVendor\Mpdf\Cache;
use WPDeskFIVendor\Mpdf\Color\ColorConverter;
use WPDeskFIVendor\Mpdf\CssManager;
use WPDeskFIVendor\Mpdf\Form;
use WPDeskFIVendor\Mpdf\Image\ImageProcessor;
use WPDeskFIVendor\Mpdf\Language\LanguageToFontInterface;
use WPDeskFIVendor\Mpdf\Mpdf;
use WPDeskFIVendor\Mpdf\Otl;
use WPDeskFIVendor\Mpdf\SizeConverter;
use WPDeskFIVendor\Mpdf\TableOfContents;
abstract class Tag
{
    use Strict;
    /**
     * @var \Mpdf\Mpdf
     */
    protected $mpdf;
    /**
     * @var \Mpdf\Cache
     */
    protected $cache;
    /**
     * @var \Mpdf\CssManager
     */
    protected $cssManager;
    /**
     * @var \Mpdf\Form
     */
    protected $form;
    /**
     * @var \Mpdf\Otl
     */
    protected $otl;
    /**
     * @var \Mpdf\TableOfContents
     */
    protected $tableOfContents;
    /**
     * @var \Mpdf\SizeConverter
     */
    protected $sizeConverter;
    /**
     * @var \Mpdf\Color\ColorConverter
     */
    protected $colorConverter;
    /**
     * @var \Mpdf\Image\ImageProcessor
     */
    protected $imageProcessor;
    /**
     * @var \Mpdf\Language\LanguageToFontInterface
     */
    protected $languageToFont;
    const ALIGN = ['left' => 'L', 'center' => 'C', 'right' => 'R', 'top' => 'T', 'text-top' => 'TT', 'middle' => 'M', 'baseline' => 'BS', 'bottom' => 'B', 'text-bottom' => 'TB', 'justify' => 'J'];
    public function __construct(\WPDeskFIVendor\Mpdf\Mpdf $mpdf, \WPDeskFIVendor\Mpdf\Cache $cache, \WPDeskFIVendor\Mpdf\CssManager $cssManager, \WPDeskFIVendor\Mpdf\Form $form, \WPDeskFIVendor\Mpdf\Otl $otl, \WPDeskFIVendor\Mpdf\TableOfContents $tableOfContents, \WPDeskFIVendor\Mpdf\SizeConverter $sizeConverter, \WPDeskFIVendor\Mpdf\Color\ColorConverter $colorConverter, \WPDeskFIVendor\Mpdf\Image\ImageProcessor $imageProcessor, \WPDeskFIVendor\Mpdf\Language\LanguageToFontInterface $languageToFont)
    {
        $this->mpdf = $mpdf;
        $this->cache = $cache;
        $this->cssManager = $cssManager;
        $this->form = $form;
        $this->otl = $otl;
        $this->tableOfContents = $tableOfContents;
        $this->sizeConverter = $sizeConverter;
        $this->colorConverter = $colorConverter;
        $this->imageProcessor = $imageProcessor;
        $this->languageToFont = $languageToFont;
    }
    public function getTagName()
    {
        $tag = \get_class($this);
        return \strtoupper(\str_replace('WPDeskFIVendor\Mpdf\Tag\\', '', $tag));
    }
    protected function getAlign($property)
    {
        $property = \strtolower($property);
        return \array_key_exists($property, self::ALIGN) ? self::ALIGN[$property] : '';
    }
    public abstract function open($attr, &$ahtml, &$ihtml);
    public abstract function close(&$ahtml, &$ihtml);
}
