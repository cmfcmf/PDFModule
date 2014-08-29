<?php

class PDF_Block_QRCode extends Zikula_Controller_AbstractBlock
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('PDF:QRCodeBlock:', 'Block title::');
    }

    /**
     * {@inheritdoc}
     */
    public function info()
    {
        return array('module' => 'PDF',
            'text_type'       => $this->__('QRCode'),
            'text_type_long'  => $this->__('Display a QRCode or Barcode.'),
            'allow_multiple'  => true,
            'form_content'    => false,
            'form_refresh'    => false,
            'show_preview'    => true,
            'admin_tableless' => true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function display($blockinfo)
    {
        // only show block content if the user has the required permissions
        if (!SecurityUtil::checkPermission('PDF:QRCodeBlock:', "$blockinfo[title]::", ACCESS_OVERVIEW) || !ModUtil::available('PDF')) {
            return false;
        }

        $cacheId = "block_qrcode_{$blockinfo['bid']}";
        $template = "Block/QRCode.tpl";

        if ($this->view->is_cached($template, $cacheId)) {
            return $this->view->fetch($template, $cacheId);
        }

        $vars = $this->getVarsFromBlockInfo($blockinfo, false);
        /** @var PDF_TCPDF_Handler $tcpdfHandler */
        $tcpdfHandler = ModUtil::apiFunc('PDF', 'PDF', 'getTCPDFHandler');

        $content = "";
        if (!empty($vars['code'])) {
            $url = $tcpdfHandler->getUrlToBarcode($vars['code'], $vars['dimension'], $vars['type'], $vars['format'], $vars['color'], $vars['width'], $vars['height']);
            $content = $this->view
                ->assign('code', $vars['code'])
                ->assign('url', $url)
                ->setCacheId($cacheId)
                ->fetch($template, $cacheId);
        }

        $blockinfo['content'] = $content;

        return BlockUtil::themeBlock($blockinfo);
    }

    /**
     * {@inheritdoc}
     */
    public function modify($blockinfo)
    {
        $vars = $this->getVarsFromBlockInfo($blockinfo, true);

        return $this->view
            ->setCaching(Zikula_View::CACHE_DISABLED)
            ->assign($vars)
            ->fetch("Block/QRCode_modify.tpl");
    }

    /**
     * {@inheritdoc}
     */
    public function update($blockinfo)
    {
        // Get current content
        $vars = $this->getVarsFromBlockInfo($blockinfo, true);

        $vars['code'] = $this->request->request->filter('code', '', FILTER_SANITIZE_STRING);
        $vars['dimension'] = $this->request->request->filter('dimension', 2, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1, "max_range" => 2)));
        $vars['type'] = $this->request->request->filter('type', 'QRCODE,H', FILTER_SANITIZE_STRING);
        $vars['color'] = $this->request->request->filter('color', 'black', FILTER_SANITIZE_STRING);
        $vars['format'] = $this->request->request->filter('format', 'album', FILTER_SANITIZE_STRING);
        $vars['width'] = $this->request->request->filter('width', '', FILTER_SANITIZE_STRING);
        $vars['height'] = $this->request->request->filter('height', '', FILTER_SANITIZE_STRING);
        $blockinfo['content'] = BlockUtil::varsToContent($vars);

        $this->view->clear_cache("Block/QRCode.tpl");

        return $blockinfo;
    }

    /**
     * Get Block variables with default values.
     *
     * @param array $blockinfo
     *
     * @return array
     */
    private function getVarsFromBlockInfo($blockinfo, $forForm)
    {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        if (!isset($vars['code']) || empty($vars['code'])) {
            $vars['code'] = '';
        }
        if (!isset($vars['dimension']) || empty($vars['dimension'])) {
            $vars['dimension'] = 2;
        }
        if (!isset($vars['type']) || empty($vars['type'])) {
            $vars['type'] = 'QRCODE,H';
        }
        if (!isset($vars['color']) || empty($vars['color'])) {
            $vars['color'] = 'black';
        }
        if (!isset($vars['format']) || empty($vars['format'])) {
            $vars['format'] = 'png';
        }
        if ($forForm && !isset($vars['width'])) {
            $vars['width'] = '';
        } else if (!$forForm && empty($vars['width'])) {
            $vars['width'] = ($vars['dimension'] == 1) ? PDF_TCPDF_Handler::BARCODE_DEFAULT_WIDTH : PDF_TCPDF_Handler::QRCODE_DEFAULT_WIDTH;
        }
        if ($forForm && !isset($vars['height'])) {
            $vars['height'] = '';
        } else if (!$forForm && empty($vars['height'])) {
            $vars['height'] = ($vars['dimension'] == 1) ? PDF_TCPDF_Handler::BARCODE_DEFAULT_HEIGHT : PDF_TCPDF_Handler::QRCODE_DEFAULT_HEIGHT;
        }

        return $vars;
    }
}
