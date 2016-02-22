PDFModule
===========

This is a helper module for PDF, QRCode and Barcode generation using the [TCPDF class for generating PDF documents and barcodes](http://www.tcpdf.org/).

## Installation

1. Install and download this module.
2. Install and download the [corresponding theme](https://github.com/cmfcmf/PDFTheme) if you want your users to be able to download every single page as PDF.

## Usage for normal users

### Bar- and QRCodes

This module provides a Content-Plugin as well as a Block to display a single Bar- / QRCode.

It is also possible (but not recommend) to generate 1D and 2D barcodes directly in templates using the `{barcode}`
function. It has the following parameters:

- code  (string)   The string to generate the barcode of.
- dimension (int)  The barcode dimension, can be either 1 or 2.
- type (string)    The type of the barcode. You can find all available types at the top of the PDF_TCPDF_Handler class.
- color (string)   The color of the bars (HTML formatted).
- width (integer)  The width of *one* bar / pixel.
- height (integer) The height of *one* bar / pixel.

Below is a list of the supported Bar- and QRCodes:

#### 1D barcode types

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

#### 2D barcode types

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

##### Examples

- `{barcode code="test"}` is the most basic usage and generates ![image](https://f.cloud.github.com/assets/2145092/376645/f8e146ea-a448-11e2-988b-f66020079cd8.png)
- `{barcode code="123abc" color='green' width='3' height='50'}` generates ![image](https://camo.githubusercontent.com/c29532b1f6eb9256a1738ee229d4936470045d03/68747470733a2f2f662e636c6f75642e6769746875622e636f6d2f6173736574732f323134353039322f3337363634362f32313661666564302d613434392d313165322d383533392d3665356261356531393164622e706e67)
- `{barcode code="https://www.github.com/cmfcmf/PDFModule" dimension=2 type='QRCODE,H'}` is the most basic usage and generates
![image](https://f.cloud.github.com/assets/2145092/376648/2a8eb6d2-a449-11e2-82c9-30c8ee250f44.png)
- `{barcode code="https://www.github.com/cmfcmf/PDFModule" color='orange' dimension=2 type='DATAMATRIX'}` generates
![image](https://f.cloud.github.com/assets/2145092/376649/39103c44-a449-11e2-9938-f680a59177e5.png)

### PDF generation (requires PDFTheme!)

#### Link in templates to download this page as PDF
Add the `{pdfLink tag=true __text='Download as PDF'}` tag to your template. This will generate a link for downloading the current page as PDF file.

#### Direct link to download a page as PDF
If you add `&theme=pdf` to any link, the page will be downloaded as PDF file.

## Usage for developers

### PDF generation
Add the two following lines of code. This will include the language files and the TCPDF config class:

```php
$tcpdfHandler = ModUtil::apiFunc('PDF', 'PDF', 'getTCPDFHandler');
```

That will directly return an instance of the PDF_TCPDF_HANDLER class. You can then call one of the following methods:
```php
/** @var $pdf TCPDF */
$pdf = $tcpdfHandler->createPDF();
```

For further documentation visit the [TCPDF documentation](http://www.tcpdf.org/doc/code/annotated.html).

### External configuration file

If you'd like to use an external configuration file, either call the `setCustomConfigFile()` method of the `$tcpdfHandler`
class with the path to your configuration file before calling `createPDF()` or place your config file at `config/tcpdf_config.php`.

*Example: If you'd like to change the `PDF_FONT_SIZE_MAIN` and `PDF_MARGIN_TOP`, your config file should look like this:*
```php
/**
 * custom top margin
 */
define ('PDF_MARGIN_TOP', 5);
/**
 * custom main font size
 */
define ('PDF_FONT_SIZE_MAIN', 30);
?>
```

# Contribute

Pull requests and issue-reportings are most welcome!

# License
MIT, see the *LICENSE.md* file.

**Important:** This project contains the TCPDF project which is licensed under GNU/LGPLv3. You find the files under `lib/vendor/tcpdf`.

**Important:** The admin icon is part of the [Free-File-Icons](https://github.com/teambox/Free-file-icons),
*Copyright (C) 2009. Teambox Technologies, S.L.* and licensed under MIT license.
