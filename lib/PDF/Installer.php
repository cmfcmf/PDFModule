<?php

class PDF_Installer extends Zikula_AbstractInstaller
{
    public function install()
    {
        return true;
    }

    public function upgrade($oldversion)
    {
        switch ($oldversion) {
            default:
                return false;
        }

        return true;
    }

    public function uninstall()
    {
        return true;
    }
}
