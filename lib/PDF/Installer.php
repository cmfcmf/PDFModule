<?php

class PDF_Installer extends Zikula_AbstractInstaller
{
    public function install()
    {
        EventUtil::registerPersistentModuleHandler('PDF', 'module.content.gettypes', array ('PDF_EventListeners', 'getContentTypes'));

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
        EventUtil::unregisterPersistentModuleHandler('PDF', 'module.content.gettypes', array ('PDF_EventListeners', 'getContentTypes'));

        return true;
    }
}
