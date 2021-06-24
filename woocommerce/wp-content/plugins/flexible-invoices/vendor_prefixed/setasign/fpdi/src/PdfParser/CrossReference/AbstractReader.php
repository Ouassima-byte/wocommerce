<?php

/**
 * This file is part of FPDI
 *
 * @package   setasign\Fpdi
 * @copyright Copyright (c) 2020 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */
namespace WPDeskFIVendor\setasign\Fpdi\PdfParser\CrossReference;

use WPDeskFIVendor\setasign\Fpdi\PdfParser\PdfParser;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfDictionary;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfToken;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfTypeException;
/**
 * Abstract class for cross-reference reader classes.
 */
abstract class AbstractReader
{
    /**
     * @var PdfParser
     */
    protected $parser;
    /**
     * @var PdfDictionary
     */
    protected $trailer;
    /**
     * AbstractReader constructor.
     *
     * @param PdfParser $parser
     * @throws CrossReferenceException
     * @throws PdfTypeException
     */
    public function __construct(\WPDeskFIVendor\setasign\Fpdi\PdfParser\PdfParser $parser)
    {
        $this->parser = $parser;
        $this->readTrailer();
    }
    /**
     * Get the trailer dictionary.
     *
     * @return PdfDictionary
     */
    public function getTrailer()
    {
        return $this->trailer;
    }
    /**
     * Read the trailer dictionary.
     *
     * @throws CrossReferenceException
     * @throws PdfTypeException
     */
    protected function readTrailer()
    {
        try {
            $trailerKeyword = $this->parser->readValue(null, \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfToken::class);
            if ($trailerKeyword->value !== 'trailer') {
                throw new \WPDeskFIVendor\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException(\sprintf('Unexpected end of cross reference. "trailer"-keyword expected, got: %s.', $trailerKeyword->value), \WPDeskFIVendor\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException::UNEXPECTED_END);
            }
        } catch (\WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfTypeException $e) {
            throw new \WPDeskFIVendor\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException('Unexpected end of cross reference. "trailer"-keyword expected, got an invalid object type.', \WPDeskFIVendor\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException::UNEXPECTED_END, $e);
        }
        try {
            $trailer = $this->parser->readValue(null, \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfDictionary::class);
        } catch (\WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfTypeException $e) {
            throw new \WPDeskFIVendor\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException('Unexpected end of cross reference. Trailer not found.', \WPDeskFIVendor\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException::UNEXPECTED_END, $e);
        }
        $this->trailer = $trailer;
    }
}
