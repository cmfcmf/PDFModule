PDFModule
===========

This is a helper module for PDF and Barcode generation using the [TCPDF class for generating PDF documents and barcodes](http://www.tcpdf.org/).

## Installation

1. Install and download this module.
2. Install and download the [corresponding theme](https://github.com/cmfcmf/PDFTheme) if you want your users to be able to download every single page as PDF.

## Usage for normal users

### PDF generation

#### Link in templates to download this page as PDF
Add the `{pdfLink tag=true __text='Download as PDF'}` tag to your template. This will generate a link for downloading the current page as PDF file.

#### Direct link to download a page as PDF
If you add `&theme=pdf` to any link, the page will be downloaded as PDF file. **Requires the Theme to be installed.**

### Barcode generation
*In short:*

- For 1D codes, add `{barcode1d code='yourCode'}` to your template.
- For 2D codes, add `{barcode2d code='yourCode'}` to your template.

*The long story:*

It is possible to generate 1D and 2D barcodes in templates. Both barcode functions have the same parameters:

- code  (string)   The string to generate the barcode of.
- type (string)    The type of the barcode.
- width (integer)  The width of *one* bar / pixel.
- height (integer) The height of *one* bar / pixel.
- color (string)   The color of the barcode. You can use…
     - …a html name here (blue, red, yellow, cornflowerblue, …).
     - …an r-g-b array.
- format (string)  The file format of the barcode.
     - png:     The barcode is generated as png file and included with an `<img>` tag (caching enabled).
     - svg:     The barcode is generated as svg file and included with an `<img>` tag (caching enabled).
     - html:    The barcode is generated out of lots of `<div>` tags. **_This is not recommend!_**
     - svgcode: The barcode is generated as svg code which can be directly used in html. **_This is not recommend!_**

#### 1D barcodes
##### Types
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
##### Examples
- `{barcode1d code="test"}` is the most basic usage and generates ![image](https://f.cloud.github.com/assets/2145092/376645/f8e146ea-a448-11e2-988b-f66020079cd8.png)
- `{barcode1d code="123abc" color='green' width='3' height='50'}` generates ![image](https://f.cloud.github.com/assets/2145092/376646/216afed0-a449-11e2-8539-6e5ba5e191db.png)

#### 2D barcodes
##### Types
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
- `{barcode2d code="https://www.github.com/cmfcmf/PDFModule"}` is the most basic usage and generates
![image](https://f.cloud.github.com/assets/2145092/376648/2a8eb6d2-a449-11e2-82c9-30c8ee250f44.png)
- `{barcode2d code="https://www.github.com/cmfcmf/PDFModule" color='orange' type='DATAMATRIX'}` generates
![image](https://f.cloud.github.com/assets/2145092/376649/39103c44-a449-11e2-9938-f680a59177e5.png)

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

If you'd like to use an external configuration file, call the `setCustomConfigFile()` method of the `$tcpdfHandler`
class with the path to your configuration file before calling `createPDF()`.

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
