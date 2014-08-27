<?php

/**
 * Available params:
 * - code  (string)   The string to generate the barcode of.
 * - type (string)    The type of the barcode. You can find all available types in Plugin.php::createBarcode1d().
 * - width (integer)  The width of *one* bar.
 * - height (integer) The height of *one* bar.
 * - color (string)   The color of the bars.
 *
 * Examples
 *
 * Basic usage:
 *  {barcode1D code='yourCode'}
 * Advanced usage:
 *  {barcode1D code='http://www.zikula.org' type='CODABAR' color='green' width=4 height=50}
 *
 * @param array       $params All attributes passed to this function from the template.
 * @param Zikula_View $view   Reference to the {@link Zikula_View} object.
 *
 * @return html barcode
 */
function smarty_function_barcode1D($params, Zikula_View $view)
{
    if (!isset($params['code'])) {
        $view->trigger_error(__f('Error! in %1$s: the %2$s parameter must be specified.', array('smarty_function_barcode', 'code')));
        return "";
    }

    $code = $params['code'];
    $type = (isset($params['type'])) ? $params['type'] : 'C128';
    $format = (isset($params['format'])) ? strtolower($params['format']) : 'png';
    $width = (isset($params['width'])) ? $params['width'] : '2';
    $height = (isset($params['height'])) ? $params['height'] : '30';
    $color = (isset($params['color'])) ? strtolower($params['color']) : 'black';

    //Force png format for pdf generation if html or svgcode is used, because html and svgcode won't work!
    $theme = UserUtil::getTheme();
    if(strtolower($theme) == 'pdf' && ($format != 'png' || $format != 'svgcode')) {
        $format = 'png';
    }

    /** @var PDF_TCPDF_Handler $tcpdf */
    $tcpdf = ModUtil::apiFunc('PDF', 'PDF', 'getTCPDFHandler');
    $result = $tcpdf->createBarcode1D($code, $type, $width, $height, $color, $format);
    
    if (isset($params['assign'])) {
        $view->assign($params['assign'], $result);

        return "";
    } else {
        return $result;
    }
}
