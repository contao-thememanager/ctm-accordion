<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_content']['fields']['accordionGroup'] = [
    'search'    => true,
    'inputType' => 'text',
    'eval'      => ['maxlength'=>64, 'tl_class'=>'w50'],
    'sql'       => "varchar(64) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['accordionOpen'] = [
    'search'    => true,
    'inputType' => 'checkbox',
    'eval'      => ['maxlength'=>64, 'tl_class'=>'w50 m12'],
    'sql'       => ['type' => 'boolean', 'default' => false]
];

PaletteManipulator::create()
    ->addField('accordionGroup', 'mooClasses')
    ->addField('accordionOpen', 'accordionGroup')
    ->applyToPalette('accordionStart', 'tl_content')
    ->applyToPalette('accordionSingle','tl_content')
;

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = ['ContaoThemeManager\Accordion\Accordion', 'showJsLibraryHint'];
