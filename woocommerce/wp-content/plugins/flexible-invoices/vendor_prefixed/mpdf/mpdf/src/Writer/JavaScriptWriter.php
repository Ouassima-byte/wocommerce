<?php

namespace WPDeskFIVendor\Mpdf\Writer;

use WPDeskFIVendor\Mpdf\Strict;
use WPDeskFIVendor\Mpdf\Mpdf;
final class JavaScriptWriter
{
    use Strict;
    /**
     * @var \Mpdf\Mpdf
     */
    private $mpdf;
    /**
     * @var \Mpdf\Writer\BaseWriter
     */
    private $writer;
    public function __construct(\WPDeskFIVendor\Mpdf\Mpdf $mpdf, \WPDeskFIVendor\Mpdf\Writer\BaseWriter $writer)
    {
        $this->mpdf = $mpdf;
        $this->writer = $writer;
    }
    public function writeJavascript()
    {
        $this->writer->object();
        $this->mpdf->n_js = $this->mpdf->n;
        $this->writer->write('<<');
        $this->writer->write('/Names [(EmbeddedJS) ' . (1 + $this->mpdf->n) . ' 0 R ]');
        $this->writer->write('>>');
        $this->writer->write('endobj');
        $this->writer->object();
        $this->writer->write('<<');
        $this->writer->write('/S /JavaScript');
        $this->writer->write('/JS ' . $this->writer->string($this->mpdf->js));
        $this->writer->write('>>');
        $this->writer->write('endobj');
    }
}
