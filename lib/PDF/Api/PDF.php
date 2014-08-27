<?php

class PDF_Api_PDF extends Zikula_AbstractApi
{
    /**
     * Returns an instance of the TCPDF Handler class. To be replaced in Zikula 1.4.
     *
     * @return PDF_TCPDF_Handler
     */
    public function getTCPDFHandler()
    {
        return new PDF_TCPDF_Handler();
    }
}
