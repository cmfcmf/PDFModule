<?php

class PDF_Controller_Admin extends Zikula_AbstractController
{
    public function postInitialize()
    {
        $this->view->setCaching(Zikula_View::CACHE_DISABLED);
    }

    /**
     * Redirects to help function.
     */
    public function main()
    {
        $this->redirect(ModUtil::url('PDF', 'admin', 'help', array(), null, null, true));
    }

    /**
     * Displays module help text.
     */
    public function help()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('PDF::', '::', ACCESS_ADMIN));

        $themes = ThemeUtil::getAllThemes(ThemeUtil::FILTER_ALL, ThemeUtil::STATE_ACTIVE);

        return $this->view
            ->assign('help', StringUtil::getMarkdownExtraParser()->transform(file_get_contents(__DIR__ . '/../../../README.md')))
            ->assign('themeInstalled', array_key_exists('PDF', $themes))
            ->fetch('Admin/help.tpl');
    }
}
