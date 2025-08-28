<?php

declare(strict_types=1);

namespace Zoglo\ContaoSimpleColumnWizard\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Zoglo\ContaoSimpleColumnWizard\ContaoSimpleColumnWizard;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            (new BundleConfig(ContaoSimpleColumnWizard::class))
                ->setLoadAfter([ContaoCoreBundle::class,])
                ->setReplace(['simplecolumnwizard']),
        ];
    }
}
