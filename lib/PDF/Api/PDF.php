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

    /**
     * Remove some elements from the given HTML by their css selectors.
     *
     * @param $args
     *
     * @throws InvalidArgumentException
     * @return string
     */
    public function removeHTMLElements($args)
    {
        if (!isset($args['html']) || !isset($args['elements'])) {
            throw new \InvalidArgumentException('Missing $html and / or $elements argument.');
        }

        require_once __DIR__ . '/../../vendor/simple_html_dom.php';

        $dom = str_get_html($args['html']);
        foreach ($args['elements'] as $selector) {
            foreach ($dom->find($selector) as $node) {
                $node->outertext = '';
            }
        }

        return (string)$dom;
    }
}
