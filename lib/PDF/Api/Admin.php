<?php

class PDF_Api_Admin extends Zikula_AbstractApi
{
    /**
     * get available admin panel links
     *
     * @return array array of admin links
     */
    public function getLinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission('PDF::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url('PDF', 'admin', 'help'),
                'text' => $this->__('Help'),
                'class' => 'z-icon-es-help');
        }

        return $links;
    }

}
