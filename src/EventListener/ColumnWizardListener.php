<?php

declare(strict_types=1);

namespace Zoglo\ContaoSimpleColumnWizard\EventListener;

use Contao\StringUtil;

class ColumnWizardListener
{
    public static function clearEmptyRowWithEmptyFirstValue($varValue)
    {
        if ($varValue === '')
        {
            return $varValue;
        }

        if (\count($arrValue = StringUtil::deserialize($varValue, true)) === 0)
        {
            return '';
        }

        if (($arrValue[0][array_key_first($arrValue[0])] ?? '') === '')
        {
            return '';
        }

        return $varValue;
    }
}
