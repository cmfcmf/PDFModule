<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

/**
 * Available 1D barcode types:
 * - C39 : CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
 * - C39+ : CODE 39 with checksum
 * - C39E : CODE 39 EXTENDED
 * - C39E+ : CODE 39 EXTENDED + CHECKSUM
 * - C93 : CODE 93 - USS-93
 * - S25 : Standard 2 of 5
 * - S25+ : Standard 2 of 5 + CHECKSUM
 * - I25 : Interleaved 2 of 5
 * - I25+ : Interleaved 2 of 5 + CHECKSUM
 * - C128 : CODE 128
 * - C128A : CODE 128 A
 * - C128B : CODE 128 B
 * - C128C : CODE 128 C
 * - EAN2 : 2-Digits UPC-Based Extention
 * - EAN5 : 5-Digits UPC-Based Extention
 * - EAN8 : EAN 8
 * - EAN13 : EAN 13
 * - UPCA : UPC-A
 * - UPCE : UPC-E
 * - MSI : MSI (Variation of Plessey code)
 * - MSI+ : MSI + CHECKSUM (modulo 11)
 * - POSTNET : POSTNET
 * - PLANET : PLANET
 * - RMS4CC : RMS4CC (Royal Mail 4-state Customer Code) - CBC (Customer Bar Code)
 * - KIX : KIX (Klant index - Customer index)
 * - IMB: Intelligent Mail Barcode - Onecode - USPS-B-3200
 * - CODABAR : CODABAR
 * - CODE11 : CODE 11
 * - PHARMA : PHARMACODE
 * - PHARMA2T : PHARMACODE TWO-TRACKS
 *
 * Available 2D barcode types:
 * - DATAMATRIX : Datamatrix (ISO/IEC 16022)
 * - PDF417 : PDF417 (ISO/IEC 15438:2006)
 * - PDF417,a,e,t,s,f,o0,o1,o2,o3,o4,o5,o6 : PDF417 with parameters: a = aspect ratio (width/height); e = error correction level (0-8); t = total number of macro segments; s = macro segment index (0-99998); f = file ID; o0 = File Name (text); o1 = Segment Count (numeric); o2 = Time Stamp (numeric); o3 = Sender (text); o4 = Addressee (text); o5 = File Size (numeric); o6 = Checksum (numeric). NOTES: Parameters t, s and f are required for a Macro Control Block, all other parametrs are optional. To use a comma character ',' on text options, replace it with the character 255: "\xff".
 * - QRCODE : QRcode Low error correction
 * - QRCODE,L : QRcode Low error correction
 * - QRCODE,M : QRcode Medium error correction
 * - QRCODE,Q : QRcode Better error correction
 * - QRCODE,H : QR-CODE Best error correction
 * - RAW: raw mode - comma-separad list of array rows
 * - RAW2: raw mode - array rows are surrounded by square parenthesis.
 * - TEST : Test matrix
 *
 * Available formats:
 * - html
 * - png
 * - svg
 */
class PDF_TCPDF_Handler
{
    private $pathToBarcodeCache;

    private $dom;

    private $tcpdfBaseDir;

    private $customConfigFile;

    const BARCODE_DEFAULT_WIDTH = 2;

    const BARCODE_DEFAULT_HEIGHT = 30;

    const QRCODE_DEFAULT_WIDTH = 6;

    const QRCODE_DEFAULT_HEIGHT = 6;

    const CACHE_DIR = 'PDFModule/barcodes';

    /**
     * DO NOT USE THIS METHOD! Always use the PDF api function to instanciate this class.
     */
    public function __construct()
    {
        $this->pathToBarcodeCache = CacheUtil::getLocalDir(self::CACHE_DIR);
        $this->tcpdfBaseDir = __DIR__ . '/../../vendor/tecnick.com/tcpdf';

        if(!is_readable($this->pathToBarcodeCache)) {
            CacheUtil::createLocalDir(self::CACHE_DIR);
            file_put_contents("{$this->pathToBarcodeCache}/.htaccess", <<< EOF
deny from all
<FilesMatch "\.(png|svg)$">
order allow,deny
allow from all
</filesmatch>
EOF
);
        }
        $this->dom = ZLanguage::getModuleDomain('PDF');
    }

    /**
     * Set a custom TCPDF configuration file.
     *
     * @param $file The full path to the configuration file.
     */
    public function setCustomConfigFile($file)
    {
        $this->customConfigFile = $file;
    }

    /**
     * Creates a new pdf file. The first seven parameters are inherited from TCPDF.
     *
     * @param $orientation (string) page orientation.
     * @param $unit        (string) User measure unit.
     * @param $format      (mixed) The format used for pages.
     * @param $unicode     (boolean) TRUE means that the input text is unicode (default = true)
     * @param $encoding    (string) Charset encoding; default is UTF-8.
     * @param $diskcache   (boolean) If TRUE reduce the RAM memory usage by caching temporary data on filesystem (slower).
     * @param $pdfa        (boolean) If TRUE set the document to PDF/A mode.
     *
     * @param $langcode    (string) The language to use in the pdf. (default = Zikula language)
     *
     * @return TCPDF
     */
    public function createPDF($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false, $langcode = '')
    {
        $this->includeLangFile($langcode);
        $this->includeConfigFile();
        if ($diskcache) {
            $this->checkTCPDFDiskCacheWritable();
        }

        $pdf = new PDF_TCPDF_TCPDF($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
        $this->setDefaultPdfConfig($pdf);

        return $pdf;
    }


    /**
     * Get the file path of the barcode relative to the Zikula root.
     *
     * @param string $code      The code to generate the barcode of.
     * @param int    $dimension The dimension, either 1 for 1D or 2 for 2D.
     * @param string $type      The type of the barcode, for available types see the class-level doc-block.
     * @param string $format    The file format of the barcode, for available types see the class-level doc-block.
     * @param string $color     The color of the bars.
     * @param int    $width     The width of *one* bar.
     * @param int    $height    The height of *oen* bar.
     *
     * @throws InvalidArgumentException
     * @return string The file path of the barcode.
     */
    public function getPathToBarcode($code, $dimension, $type, $format = 'png', $color = 'black', $width = self::BARCODE_DEFAULT_WIDTH, $height = self::BARCODE_DEFAULT_HEIGHT)
    {
        if (!in_array($format, array('png', 'svg'))) {
            throw new \InvalidArgumentException('$format must be either "png" or "svg".');
        }
        $typeForPath = preg_replace('/[^a-zA-Z0-9_-]/', '_', $type);
        list ($r, $g, $b) = $this->convertColor($color, 'png');
        $path = self::CACHE_DIR . "/$dimension/{$typeForPath}/{$width}_{$height}_{$r}_{$g}_{$b}";
        $file = CacheUtil::getLocalDir($path) . "/" . md5($code) . ".$format";
        if (file_exists($file)) {
            return $file;
        }
        $data = $this->getBarcodeData($code, $dimension, $type, $format, $color, $width, $height);

        CacheUtil::createLocalDir($path);
        file_put_contents($file, $data);

        return $file;
    }

    /**
     * Get the url of the barcode.
     *
     * @param string $code      The code to generate the barcode of.
     * @param int    $dimension The dimension, either 1 for 1D or 2 for 2D.
     * @param string $type      The type of the barcode, for available types see the class-level doc-block.
     * @param string $format    The file format of the barcode, for available types see the class-level doc-block.
     * @param string $color     The color of the bars.
     * @param int    $width     The width of *one* bar.
     * @param int    $height    The height of *oen* bar.
     *
     * @throws InvalidArgumentException
     * @return string The url of the barcode.
     */
    public function getUrlToBarcode($code, $dimension, $type, $format = 'png', $color = 'black', $width = self::BARCODE_DEFAULT_WIDTH, $height = self::BARCODE_DEFAULT_HEIGHT)
    {
        $path = $this->getPathToBarcode($code, $dimension, $type, $format, $color, $width, $height);

        return System::getBaseUrl() . $path;
    }

    /**
     * Get the raw data of a barcode.
     *
     * @param string $code      The code to generate the barcode of.
     * @param int    $dimension The dimension, either 1 for 1D or 2 for 2D.
     * @param string $type      The type of the barcode, for available types see the class-level doc-block.
     * @param string $format    The file format of the barcode, for available types see the class-level doc-block.
     * @param string $color     The color of the bars.
     * @param int    $width     The width of *one* bar.
     * @param int    $height    The height of *oen* bar.
     *
     * @throws InvalidArgumentException
     * @return string The raw data of a barcode.
     */
    public function getBarcodeData($code, $dimension, $type, $format = 'png', $color = 'black', $width = self::BARCODE_DEFAULT_WIDTH, $height = self::BARCODE_DEFAULT_HEIGHT)
    {
        $this->includeConfigFile();
        $color = $this->convertColor($color, $format);

        if ($dimension == 1) {
            $barcode = new TCPDFBarcode($code, $type);
        } else if ($dimension == 2) {
            $barcode = new TCPDF2DBarcode($code, $type);
        } else {
            throw new \InvalidArgumentException('$dimension must be either 1 or 2.');
        }

        switch($format) {
            case 'html':
                return $barcode->getBarcodeHTML($width, $height, $color);
            case 'svg':
                return $barcode->getBarcodeSVGcode($width, $height, $color);
            case 'png':
                return $barcode->getBarcodePngData($width, $height, $color);
            default:
                throw new \InvalidArgumentException('$format is invalid!');
        }
    }

    /**
     * Check if the TCPDF disk cache is writable.
     */
    private function checkTCPDFDiskCacheWritable()
    {
        $tcpdfCacheDir = K_PATH_CACHE;
        if (!is_writable($tcpdfCacheDir)) {
            throw new \RuntimeException($this->__("$tcpdfCacheDir must be writeable!"));
        }
    }

    /**
     * Includes the TCPDF language file.
     *
     * @param $langcode (string) The language to use in the pdf. (default = system language)
     */
    private function includeLangFile($langcode)
    {
        if (empty($langcode)) {
            $lang = ZLanguage::getInstance();
            $langcode = $lang->getLanguageCodeLegacy();
        }

        if ($langcode == 'deu') {
            $langcode = 'ger';
        }
        $langfile = "{$this->tcpdfBaseDir}/examples/lang/{$langcode}.php";
        if (!file_exists($langfile)) {
            $langfile = "{$this->tcpdfBaseDir}/examples/lang/eng.php";
        }
        require_once($langfile);
    }

    /**
     * Includes the TCPDF config file.
     */
    private function includeConfigFile()
    {
        // Include custom config file.
        require_once __DIR__ . "/../../PDF/TCPDF/tcpdf_custom_config.php";
        if (is_readable("config/tcpdf_config.php")) {
            require_once "config/tcpdf_config.php";
        }
        if (!empty($this->customConfigFile)) {
            // Include custom third party config file.
            require_once $this->customConfigFile;
        }
    }

    /**
     * Sets some handy default PDF values.
     *
     * @param TCPDF $pdf
     */
    private function setDefaultPdfConfig(TCPDF &$pdf)
    {
        global $l; //Used by TCPDF for language

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle(PDF_HEADER_TITLE);
        $pdf->SetSubject(PageUtil::getVar('title'));
        $pdf->SetKeywords(System::getVar('metakeywords'));

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_HEADER, '', PDF_FONT_SIZE_HEADER));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        //set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        //set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        //set some language-dependent strings
        $pdf->setLanguageArray($l);

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        $pdf->SetFont(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN);
    }

    private function convertColor($color, $format)
    {
        if ($format != 'png') {
            return $color;
        }

        if (preg_match('/^#[0-9a-fA-F]{3}(?:[0-9a-fA-F]{3})?$/', $color) === 0) {
            $colorArray = TCPDF_COLORS::$webcolor;
            $hex = $colorArray[$color];
        } else {
            $hex = $color;
        }

        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }

        return array($r, $g, $b);
    }

    /**
     * singular translation for modules.
     *
     * @param string $msg Message.
     *
     * @return string
     */
    private function __($msg)
    {
        return __($msg, $this->dom);
    }
}
