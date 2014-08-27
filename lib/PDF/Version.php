<?php

class PDF_Version extends Zikula_AbstractVersion
{
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname'] = $this->__('PDF');
        $meta['description'] = $this->__('Helper module for PDF and Barcode generation using the TCPDF library.');
        $meta['version'] = '1.0.0';
        $meta['url'] = $this->__('pdf');
        $meta['core_min'] = '1.3.0';
        $meta['core_max'] = '1.4.99';
        $meta['securityschema'] = array('PDF::' => '::');

        return $meta;
    }
}
