<?php

use Contao\Config;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\System;

/**
 * Get bundle information
 */
$bundles = System::getContainer()->getParameter('kernel.bundles');

/**
 * Add FAQ bundle related fields
 */
if (isset($bundles['ContaoFaqBundle']))
{
    $GLOBALS['TL_DCA']['tl_module']['fields']['faqAccordion'] = [
        'exclude'     => true,
        'inputType'   => 'select',
        'options'     => ['single_open', 'single_closed', 'multi_open', 'multi_closed'],
        'reference'   => &$GLOBALS['TL_LANG']['tl_module']['faqAccordion'],
        'eval'        => ['tl_class'=>'w50'],
        'sql'         => "varchar(16) COLLATE ascii_bin NOT NULL default 'single_open'"
    ];

    $GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][] = ['ContaoThemeManager\Accordion\Accordion', 'extendFaqAccordionSettings'];
}
