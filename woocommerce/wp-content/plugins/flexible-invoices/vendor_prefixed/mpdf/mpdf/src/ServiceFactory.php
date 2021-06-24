<?php

namespace WPDeskFIVendor\Mpdf;

use WPDeskFIVendor\Mpdf\Color\ColorConverter;
use WPDeskFIVendor\Mpdf\Color\ColorModeConverter;
use WPDeskFIVendor\Mpdf\Color\ColorSpaceRestrictor;
use WPDeskFIVendor\Mpdf\Fonts\FontCache;
use WPDeskFIVendor\Mpdf\Fonts\FontFileFinder;
use WPDeskFIVendor\Mpdf\Image\ImageProcessor;
use WPDeskFIVendor\Mpdf\Pdf\Protection;
use WPDeskFIVendor\Mpdf\Pdf\Protection\UniqidGenerator;
use WPDeskFIVendor\Mpdf\Writer\BaseWriter;
use WPDeskFIVendor\Mpdf\Writer\BackgroundWriter;
use WPDeskFIVendor\Mpdf\Writer\ColorWriter;
use WPDeskFIVendor\Mpdf\Writer\BookmarkWriter;
use WPDeskFIVendor\Mpdf\Writer\FontWriter;
use WPDeskFIVendor\Mpdf\Writer\FormWriter;
use WPDeskFIVendor\Mpdf\Writer\ImageWriter;
use WPDeskFIVendor\Mpdf\Writer\JavaScriptWriter;
use WPDeskFIVendor\Mpdf\Writer\MetadataWriter;
use WPDeskFIVendor\Mpdf\Writer\OptionalContentWriter;
use WPDeskFIVendor\Mpdf\Writer\PageWriter;
use WPDeskFIVendor\Mpdf\Writer\ResourceWriter;
use Psr\Log\LoggerInterface;
class ServiceFactory
{
    public function getServices(\WPDeskFIVendor\Mpdf\Mpdf $mpdf, \Psr\Log\LoggerInterface $logger, $config, $restrictColorSpace, $languageToFont, $scriptToLanguage, $fontDescriptor, $bmp, $directWrite, $wmf)
    {
        $sizeConverter = new \WPDeskFIVendor\Mpdf\SizeConverter($mpdf->dpi, $mpdf->default_font_size, $mpdf, $logger);
        $colorModeConverter = new \WPDeskFIVendor\Mpdf\Color\ColorModeConverter();
        $colorSpaceRestrictor = new \WPDeskFIVendor\Mpdf\Color\ColorSpaceRestrictor($mpdf, $colorModeConverter, $restrictColorSpace);
        $colorConverter = new \WPDeskFIVendor\Mpdf\Color\ColorConverter($mpdf, $colorModeConverter, $colorSpaceRestrictor);
        $tableOfContents = new \WPDeskFIVendor\Mpdf\TableOfContents($mpdf, $sizeConverter);
        $cache = new \WPDeskFIVendor\Mpdf\Cache($config['tempDir']);
        $fontCache = new \WPDeskFIVendor\Mpdf\Fonts\FontCache(new \WPDeskFIVendor\Mpdf\Cache($config['tempDir'] . '/ttfontdata'));
        $fontFileFinder = new \WPDeskFIVendor\Mpdf\Fonts\FontFileFinder($config['fontDir']);
        $cssManager = new \WPDeskFIVendor\Mpdf\CssManager($mpdf, $cache, $sizeConverter, $colorConverter);
        $otl = new \WPDeskFIVendor\Mpdf\Otl($mpdf, $fontCache);
        $protection = new \WPDeskFIVendor\Mpdf\Pdf\Protection(new \WPDeskFIVendor\Mpdf\Pdf\Protection\UniqidGenerator());
        $writer = new \WPDeskFIVendor\Mpdf\Writer\BaseWriter($mpdf, $protection);
        $gradient = new \WPDeskFIVendor\Mpdf\Gradient($mpdf, $sizeConverter, $colorConverter, $writer);
        $formWriter = new \WPDeskFIVendor\Mpdf\Writer\FormWriter($mpdf, $writer);
        $form = new \WPDeskFIVendor\Mpdf\Form($mpdf, $otl, $colorConverter, $writer, $formWriter);
        $hyphenator = new \WPDeskFIVendor\Mpdf\Hyphenator($mpdf);
        $remoteContentFetcher = new \WPDeskFIVendor\Mpdf\RemoteContentFetcher($mpdf, $logger);
        $imageProcessor = new \WPDeskFIVendor\Mpdf\Image\ImageProcessor($mpdf, $otl, $cssManager, $sizeConverter, $colorConverter, $colorModeConverter, $cache, $languageToFont, $scriptToLanguage, $remoteContentFetcher, $logger);
        $tag = new \WPDeskFIVendor\Mpdf\Tag($mpdf, $cache, $cssManager, $form, $otl, $tableOfContents, $sizeConverter, $colorConverter, $imageProcessor, $languageToFont);
        $fontWriter = new \WPDeskFIVendor\Mpdf\Writer\FontWriter($mpdf, $writer, $fontCache, $fontDescriptor);
        $metadataWriter = new \WPDeskFIVendor\Mpdf\Writer\MetadataWriter($mpdf, $writer, $form, $protection, $logger);
        $imageWriter = new \WPDeskFIVendor\Mpdf\Writer\ImageWriter($mpdf, $writer);
        $pageWriter = new \WPDeskFIVendor\Mpdf\Writer\PageWriter($mpdf, $form, $writer, $metadataWriter);
        $bookmarkWriter = new \WPDeskFIVendor\Mpdf\Writer\BookmarkWriter($mpdf, $writer);
        $optionalContentWriter = new \WPDeskFIVendor\Mpdf\Writer\OptionalContentWriter($mpdf, $writer);
        $colorWriter = new \WPDeskFIVendor\Mpdf\Writer\ColorWriter($mpdf, $writer);
        $backgroundWriter = new \WPDeskFIVendor\Mpdf\Writer\BackgroundWriter($mpdf, $writer);
        $javaScriptWriter = new \WPDeskFIVendor\Mpdf\Writer\JavaScriptWriter($mpdf, $writer);
        $resourceWriter = new \WPDeskFIVendor\Mpdf\Writer\ResourceWriter($mpdf, $writer, $colorWriter, $fontWriter, $imageWriter, $formWriter, $optionalContentWriter, $backgroundWriter, $bookmarkWriter, $metadataWriter, $javaScriptWriter, $logger);
        return ['otl' => $otl, 'bmp' => $bmp, 'cache' => $cache, 'cssManager' => $cssManager, 'directWrite' => $directWrite, 'fontCache' => $fontCache, 'fontFileFinder' => $fontFileFinder, 'form' => $form, 'gradient' => $gradient, 'tableOfContents' => $tableOfContents, 'tag' => $tag, 'wmf' => $wmf, 'sizeConverter' => $sizeConverter, 'colorConverter' => $colorConverter, 'hyphenator' => $hyphenator, 'remoteContentFetcher' => $remoteContentFetcher, 'imageProcessor' => $imageProcessor, 'protection' => $protection, 'languageToFont' => $languageToFont, 'scriptToLanguage' => $scriptToLanguage, 'writer' => $writer, 'fontWriter' => $fontWriter, 'metadataWriter' => $metadataWriter, 'imageWriter' => $imageWriter, 'formWriter' => $formWriter, 'pageWriter' => $pageWriter, 'bookmarkWriter' => $bookmarkWriter, 'optionalContentWriter' => $optionalContentWriter, 'colorWriter' => $colorWriter, 'backgroundWriter' => $backgroundWriter, 'javaScriptWriter' => $javaScriptWriter, 'resourceWriter' => $resourceWriter];
    }
}
