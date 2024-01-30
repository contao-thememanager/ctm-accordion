<?php

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Accordion;

use Contao\Backend;
use Contao\ContentModel;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\DataContainer;
use Contao\Input;
use Contao\Message;
use Contao\System;

class Accordion extends Backend
{
    const NAME_SM_CONFIG = 'tm-config';

    private string $rootDir;

    public function __construct()
    {
        parent::__construct();
        System::loadLanguageFile('tl_thememanager_settings');
        $this->rootDir = System::getContainer()->getParameter('kernel.project_dir');
    }

    /**
     * Extends faq page with accordion settings based on the customTpl selection
     */
    public function extendFaqAccordionSettings(DataContainer $dc): void
    {
        $module = 'faqpage';

        if (array_key_exists($module, $GLOBALS['TL_DCA'][$dc->table]['palettes']))
        {
            $palette = $GLOBALS['TL_DCA'][$dc->table]['palettes'][$module];

            if (!is_array($palette) && strpos($palette, $module) === false && strpos($palette, 'faq_categories') !== false)
            {
                // Get module
                $objModule = $this->Database->prepare("SELECT * FROM " . $dc->table . " WHERE id=?")
                    ->limit(1)
                    ->execute($dc->id);

                if (null !== ($template = $objModule->customTpl) && str_contains($template, 'accordion'))
                {
                    PaletteManipulator::create()
                        ->addField(['faqAccordion'], 'faq_categories')
                        ->applyToPalette($module, $dc->table);

                    Message::addInfo(sprintf(($GLOBALS['TL_LANG']['tl_thememanager_settings']['includeCtmTemplate'] ?? null), 'js_ctm_accordion'));
                }
            }
        }
    }

    /**
     * Replaces the native js library hint
     */
    public function showJsLibraryHint(DataContainer $dc): void
    {
        if ($_POST || Input::get('act') != 'edit')
        {
            return;
        }

        $security = System::getContainer()->get('security.helper');

        // Return if the user cannot access the layout module (see #6190)
        if (!$security->isGranted(ContaoCorePermissions::USER_CAN_ACCESS_MODULE, 'themes') || !$security->isGranted(ContaoCorePermissions::USER_CAN_ACCESS_LAYOUTS))
        {
            return;
        }

        $objCte = ContentModel::findByPk($dc->id);

        if ($objCte === null)
        {
            return;
        }

        switch ($objCte->type)
        {
            case 'accordionSingle':
            case 'accordionStart':
            case 'accordionStop':
                // Check whether info exists and replace it
                if (Message::hasInfo())
                {
                    $session = System::getContainer()->get('request_stack')->getSession();

                    if (!$session->isStarted())
                    {
                        return;
                    }

                    ($session->getFlashBag())->get('contao.BE.info');
                }

                Message::addInfo(sprintf(($GLOBALS['TL_LANG']['tl_thememanager_settings']['includeCtmTemplate'] ?? null), 'js_ctm_accordion'));
                break;
        }
    }
}
