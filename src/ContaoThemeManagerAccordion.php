<?php

declare(strict_types=1);

namespace ContaoThemeManager\Accordion;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoThemeManagerAccordion extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
