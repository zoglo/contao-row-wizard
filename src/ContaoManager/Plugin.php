<?php

declare(strict_types=1);

namespace Zoglo\SimpleColumnWizardBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Zoglo\SimpleColumnWizardBundle\ZogloSimpleColumnWizardBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            (new BundleConfig(ZogloSimpleColumnWizardBundle::class))
                ->setLoadAfter([ContaoCoreBundle::class])
        ];
    }
}
