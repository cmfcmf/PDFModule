<?php

/**
 * Available params:
 * - code  (string)   The string to generate the barcode of.
 * - dimension (int)  The barcode dimension, can be either 1 or 2.
 * - type (string)    The type of the barcode. You can find all available types at the top of the PDF_TCPDF_Handler class.
 * - color (string)   The color of the bars (HTML formatted).
 * - width (integer)  The width of *one* bar / pixel.
 * - height (integer) The height of *one* bar / pixel.
 *
 * Examples:
 *
 * Basic usage:
 *  {barcode code='yourCode'}
 * Advanced usage:
 *  {barcode code='http://www.zikula.org' dimension=2 type='QRCODE,H' color='green' width=7 height=7}
 *
 * @param array       $params All attributes passed to this function from the template.
 * @param Zikula_View $view   Reference to the {@link Zikula_View} object.
 *
 * @return html barcode
 */
function smarty_function_barcode($params, Zikula_View $view)
{
    if (!isset($params['code'])) {
        $view->trigger_error(__f('Error! in %1$s: the %2$s parameter must be specified.', array('smarty_function_barcode', 'code')));
        return "";
    }

    $code = $params['code'];
    $dimension = (isset($params['dimension'])) ? $params['dimension'] : '2';
    $type = (!isset($params['dimension']) && !isset($params['type'])) ? 'QRCODE,H' : $params['type'];
    $format = (isset($params['format'])) ? strtolower($params['format']) : 'png';
    $color = (isset($params['color'])) ? strtolower($params['color']) : 'black';
    $width = (isset($params['width'])) ? $params['width'] : (($dimension == 1) ? PDF_TCPDF_Handler::BARCODE_DEFAULT_WIDTH : PDF_TCPDF_Handler::QRCODE_DEFAULT_WIDTH);
    $height = (isset($params['height'])) ? $params['height'] : (($dimension == 1) ? PDF_TCPDF_Handler::BARCODE_DEFAULT_HEIGHT : PDF_TCPDF_Handler::QRCODE_DEFAULT_HEIGHT);

    /** @var PDF_TCPDF_Handler $tcpdf */
    $tcpdf = ModUtil::apiFunc('PDF', 'PDF', 'getTCPDFHandler');
    $result = $tcpdf->getUrlToBarcode($code, $dimension, $type, $format, $color, $width, $height);
    
    if (isset($params['assign'])) {
        $view->assign($params['assign'], $result);

        return "";
    } else {
        return $result;
    }
}
