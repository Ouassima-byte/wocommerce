<?php

namespace WPDeskFIVendor\Mpdf\Language;

use WPDeskFIVendor\Mpdf\Ucdn;
class ScriptToLanguage implements \WPDeskFIVendor\Mpdf\Language\ScriptToLanguageInterface
{
    private $scriptDelimiterMap = [
        'viet' => "\\x{01A0}\\x{01A1}\\x{01AF}\\x{01B0}\\x{1EA0}-\\x{1EF1}",
        'persian' => "\\x{067E}\\x{0686}\\x{0698}\\x{06AF}",
        'urdu' => "\\x{0679}\\x{0688}\\x{0691}\\x{06BA}\\x{06BE}\\x{06C1}\\x{06D2}",
        'pashto' => "\\x{067C}\\x{0681}\\x{0685}\\x{0689}\\x{0693}\\x{0696}\\x{069A}\\x{06BC}\\x{06D0}",
        // ? and U+06AB, U+06CD
        'sindhi' => "\\x{067A}\\x{067B}\\x{067D}\\x{067F}\\x{0680}\\x{0684}\\x{068D}\\x{068A}\\x{068F}\\x{068C}\\x{0687}\\x{0683}\\x{0699}\\x{06AA}\\x{06A6}\\x{06BB}\\x{06B1}\\x{06B3}",
    ];
    private $scriptToLanguageMap = [
        /* European */
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_LATIN => 'und-Latn',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_ARMENIAN => 'hy',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_CYRILLIC => 'und-Cyrl',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_GEORGIAN => 'ka',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_GREEK => 'el',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_COPTIC => 'cop',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_GOTHIC => 'got',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_CYPRIOT => 'und-Cprt',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_GLAGOLITIC => 'und-Glag',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_LINEAR_B => 'und-Linb',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_OGHAM => 'und-Ogam',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_OLD_ITALIC => 'und-Ital',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_RUNIC => 'und-Runr',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_SHAVIAN => 'und-Shaw',
        /* African */
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_ETHIOPIC => 'und-Ethi',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_NKO => 'nqo',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_BAMUM => 'bax',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_VAI => 'vai',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_EGYPTIAN_HIEROGLYPHS => 'und-Egyp',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_MEROITIC_CURSIVE => 'und-Merc',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_MEROITIC_HIEROGLYPHS => 'und-Mero',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_OSMANYA => 'und-Osma',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_TIFINAGH => 'und-Tfng',
        /* Middle Eastern */
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_ARABIC => 'und-Arab',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_HEBREW => 'he',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_SYRIAC => 'syr',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_IMPERIAL_ARAMAIC => 'arc',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_AVESTAN => 'ae',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_CARIAN => 'xcr',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_LYCIAN => 'xlc',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_LYDIAN => 'xld',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_MANDAIC => 'mid',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_OLD_PERSIAN => 'peo',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_PHOENICIAN => 'phn',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_SAMARITAN => 'smp',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_UGARITIC => 'uga',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_CUNEIFORM => 'und-Xsux',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_OLD_SOUTH_ARABIAN => 'und-Sarb',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_INSCRIPTIONAL_PARTHIAN => 'und-Prti',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_INSCRIPTIONAL_PAHLAVI => 'und-Phli',
        /* Central Asian */
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_MONGOLIAN => 'mn',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_TIBETAN => 'bo',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_OLD_TURKIC => 'und-Orkh',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_PHAGS_PA => 'und-Phag',
        /* South Asian */
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_BENGALI => 'bn',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_DEVANAGARI => 'hi',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_GUJARATI => 'gu',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_GURMUKHI => 'pa',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_KANNADA => 'kn',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_MALAYALAM => 'ml',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_ORIYA => 'or',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_SINHALA => 'si',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_TAMIL => 'ta',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_TELUGU => 'te',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_CHAKMA => 'ccp',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_LEPCHA => 'lep',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_LIMBU => 'lif',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_OL_CHIKI => 'sat',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_SAURASHTRA => 'saz',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_SYLOTI_NAGRI => 'syl',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_TAKRI => 'dgo',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_THAANA => 'dv',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_BRAHMI => 'und-Brah',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_KAITHI => 'und-Kthi',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_KHAROSHTHI => 'und-Khar',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_MEETEI_MAYEK => 'und-Mtei',
        /* or omp-Mtei */
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_SHARADA => 'und-Shrd',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_SORA_SOMPENG => 'und-Sora',
        /* South East Asian */
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_KHMER => 'km',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_LAO => 'lo',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_MYANMAR => 'my',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_THAI => 'th',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_BALINESE => 'ban',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_BATAK => 'bya',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_BUGINESE => 'bug',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_CHAM => 'cjm',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_JAVANESE => 'jv',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_KAYAH_LI => 'und-Kali',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_REJANG => 'und-Rjng',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_SUNDANESE => 'su',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_TAI_LE => 'tdd',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_TAI_THAM => 'und-Lana',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_TAI_VIET => 'blt',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_NEW_TAI_LUE => 'und-Talu',
        /* Phillipine */
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_BUHID => 'bku',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_HANUNOO => 'hnn',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_TAGALOG => 'tl',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_TAGBANWA => 'tbw',
        /* East Asian */
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_HAN => 'und-Hans',
        // und-Hans (simplified) or und-Hant (Traditional)
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_HANGUL => 'ko',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_HIRAGANA => 'ja',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_KATAKANA => 'ja',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_LISU => 'lis',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_BOPOMOFO => 'und-Bopo',
        // zh-CN, zh-TW, zh-HK
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_MIAO => 'und-Plrd',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_YI => 'und-Yiii',
        /* American */
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_CHEROKEE => 'chr',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_CANADIAN_ABORIGINAL => 'cr',
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_DESERET => 'und-Dsrt',
        /* Other */
        \WPDeskFIVendor\Mpdf\Ucdn::SCRIPT_BRAILLE => 'und-Brai',
    ];
    public function getLanguageByScript($script)
    {
        return isset($this->scriptToLanguageMap[$script]) ? $this->scriptToLanguageMap[$script] : null;
    }
    public function getLanguageDelimiters($language)
    {
        return isset($this->scriptDelimiterMap[$language]) ? $this->scriptDelimiterMap[$language] : null;
    }
}
