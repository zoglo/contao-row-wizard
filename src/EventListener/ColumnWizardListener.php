<?php

declare(strict_types=1);

namespace Zoglo\RowWizardBundle\EventListener;

use Contao\StringUtil;

class ColumnWizardListener
{
    public static function clearEmptyRowWithEmptyFirstValue($var): string
    {
        if ('' === $var) {
            return $var;
        }

        if (0 === \count($values = StringUtil::deserialize($var, true))) {
            return '';
        }

        // Do not reset if there is more than one row
        if (1 !== \count($values)) {
            return $var;
        }

        if (($values[0][array_key_first($values[0])] ?? '') === '') {
            return '';
        }

        return $var;
    }
}
