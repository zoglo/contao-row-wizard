<?php

declare(strict_types=1);

namespace Zoglo\RowWizardBundle\EventListener;

use Contao\StringUtil;

class ColumnWizardListener
{
    public static function clearEmptyRowWithEmptyFirstValue($var): string
    {
        if ($var === '') {
            return $var;
        }

        if (\count($values = StringUtil::deserialize($var, true)) === 0) {
            return '';
        }

        // Do not reset if there is more than one row
        if (\count($values) !== 1) {
            return $var;
        }

        if (($values[0][array_key_first($values[0])] ?? '') === '') {
            return '';
        }

        return $var;
    }
}
