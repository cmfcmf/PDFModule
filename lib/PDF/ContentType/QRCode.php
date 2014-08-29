<?php

class PDF_ContentType_QRCode extends Content_AbstractContentType
{
    private $data;

    public function getTitle()
    {
        return $this->__('QRCode / Barcode');
    }

    public function getDescription()
    {
        return $this->__('Display a QRCode or Barcode.');
    }

    public function isTranslatable()
    {
        return false;
    }

    public function loadData(&$data)
    {
        $this->data = $data;
    }

    public function startEditing()
    {
        $this->view->assign($this->data);
    }

    public function display()
    {
        $vars = $this->data;
        if (empty($vars['code'])) {
            return "";
        }
        if (empty($vars['width'])) {
            $vars['width'] = ($vars['dimension'] == 1) ? PDF_TCPDF_Handler::BARCODE_DEFAULT_WIDTH : PDF_TCPDF_Handler::QRCODE_DEFAULT_WIDTH;
        }
        if (empty($vars['height'])) {
            $vars['height'] = ($vars['dimension'] == 1) ? PDF_TCPDF_Handler::BARCODE_DEFAULT_HEIGHT : PDF_TCPDF_Handler::QRCODE_DEFAULT_HEIGHT;
        }

        /** @var PDF_TCPDF_Handler $tcpdfHandler */
        $tcpdfHandler = ModUtil::apiFunc('PDF', 'PDF', 'getTCPDFHandler');
        $url = $tcpdfHandler->getUrlToBarcode($vars['code'], $vars['dimension'],  $vars['codeType'], $vars['qrFormat'], $vars['color'], $vars['width'], $vars['height']);
        $this->view->assign('url', $url);
        $this->view->assign('code', $vars['code']);

        return $this->view->fetch($this->getTemplate());
    }

    public function getDefaultData()
    {
        $vars = array();

        $vars['code'] = '';
        $vars['dimension'] = 2;
        $vars['codeType'] = 'QRCODE,H';
        $vars['color'] = 'black';
        $vars['qrFormat'] = 'png';
        $vars['width'] = '';
        $vars['height'] = '';
        
        return $vars;
    }
}
