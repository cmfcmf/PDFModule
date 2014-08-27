<?php

// Tell TCPDF that we use a custom configuration.
define('K_TCPDF_EXTERNAL_CONFIG', true);

// Set the TCPDF cache to a known location.
define('K_PATH_CACHE', CacheUtil::getLocalDir('PDFModule/TCPDFCache'));
CacheUtil::createLocalDir('PDFModule/TCPDFCache');

define('K_PATH_IMAGES', realpath(__DIR__ . '/../../../../..') . '/');

/**
 * Deafult image logo used be the default Header() method.
 * Please set here your own logo or an empty string to disable it.
 */
define ('PDF_HEADER_LOGO', 'images/logo.gif');

/**
 * Header logo image width in user units.
 */
define ('PDF_HEADER_LOGO_WIDTH', 15);

/**
 * blank image
 */
define ('K_BLANK_IMAGE', '_blank.png');

/**
 * page format
 */
define ('PDF_PAGE_FORMAT', 'A4');

/**
 * Page orientation (P=portrait, L=landscape).
 */
define ('PDF_PAGE_ORIENTATION', 'P');

/**
 * Document creator.
 */
define ('PDF_CREATOR', 'TCPDF');

/**
 * Document author.
 */
define ('PDF_AUTHOR', System::getVar('sitename'));

/**
 * Header title.
 */
define ('PDF_HEADER_TITLE', PageUtil::getVar('title'));

/**
 * Header description string.
 */
define ('PDF_HEADER_STRING', "by " . System::getVar('sitename') . "\n" . System::getVar('defaultmetadescription'));

/**
 * Document unit of measure [pt=point, mm=millimeter, cm=centimeter, in=inch].
 */
define ('PDF_UNIT', 'mm');

/**
 * Header margin.
 */
define ('PDF_MARGIN_HEADER', 5);

/**
 * Footer margin.
 */
define ('PDF_MARGIN_FOOTER', 10);

/**
 * Top margin.
 */
define ('PDF_MARGIN_TOP', 20);

/**
 * Bottom margin.
 */
define ('PDF_MARGIN_BOTTOM', 15);

/**
 * Left margin.
 */
define ('PDF_MARGIN_LEFT', 20);

/**
 * Right margin.
 */
define ('PDF_MARGIN_RIGHT', 10);

/**
 * Default main font name.
 */
define ('PDF_FONT_NAME_MAIN', 'dejavusanscondensed');

/**
 * Default main font size.
 */
define ('PDF_FONT_SIZE_MAIN', 9);

/**
 * Default header font name.
 */
define ('PDF_FONT_NAME_HEADER', 'dejavusanscondensed');

/**
 * default main font size
 */
define ('PDF_FONT_SIZE_HEADER', 11);

/**
 * default data font name
 */
define ('PDF_FONT_NAME_DATA', 'dejavusans');

/**
 * Default data font size.
 */
define ('PDF_FONT_SIZE_DATA', 8);

/**
 * Default monospaced font name.
 */
define ('PDF_FONT_MONOSPACED', 'dejavusansmono');

/**
 * Ratio used to adjust the conversion of pixels to user units.
 */
define ('PDF_IMAGE_SCALE_RATIO', 1.25);

/**
 * Magnification factor for titles.
 */
define('HEAD_MAGNIFICATION', 1.1);

/**
 * Height of cell respect font height.
 */
define('K_CELL_HEIGHT_RATIO', 1.25);

/**
 * Title magnification respect main font size.
 */
define('K_TITLE_MAGNIFICATION', 1.3);

/**
 * Reduction factor for small font.
 */
define('K_SMALL_RATIO', 2 / 3);

/**
 * Set to true to enable the special procedure used to avoid the overlappind of symbols on Thai language.
 */
define('K_THAI_TOPCHARS', true);

/**
 * If true allows to call TCPDF methods using HTML syntax
 * IMPORTANT: For security reason, disable this feature if you are printing user HTML content.
 */
define('K_TCPDF_CALLS_IN_HTML', true);

/**
 * If true and PHP version is greater than 5, then the Error() method throw new exception instead of terminating the execution.
 */
define('K_TCPDF_THROW_EXCEPTION_ERROR', true);
