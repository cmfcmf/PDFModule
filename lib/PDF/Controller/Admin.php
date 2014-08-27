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

        return $this->view
            ->assign('help', StringUtil::getMarkdownExtraParser()->transform(file_get_contents(__DIR__ . '/../../../README.md')))
            ->assign('themeInstalled', $this->isPDFThemeInstalled())
            ->fetch('Admin/help.tpl');
    }

    /**
     * Test some PDF functions.
     */
    public function test()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('PDF::', '::', ACCESS_ADMIN));

        return $this->view
            ->assign('themeInstalled', $this->isPDFThemeInstalled())
            ->fetch('Admin/test.tpl');
    }

    private function isPDFThemeInstalled()
    {
        $themes = ThemeUtil::getAllThemes(ThemeUtil::FILTER_ALL, ThemeUtil::STATE_ACTIVE);

        return array_key_exists('PDF', $themes);
    }
}
