<?php

declare(strict_types=1);

namespace Zoglo\RowWizardBundle\EventListener;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\Asset\Packages;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener]
class AddAssetsListener
{
    public function __construct(private readonly ScopeMatcher $scopeMatcher, private readonly Packages $package)
    {
    }

    public function __invoke(RequestEvent $event): void
    {
        if ($this->scopeMatcher->isBackendMainRequest($event)) {
            $GLOBALS['TL_JAVASCRIPT'][] = $this->package->getUrl('row-wizard.js', 'zoglo_row_wizard');
            $GLOBALS['TL_CSS'][] = $this->package->getUrl('row-wizard.css', 'zoglo_row_wizard');
        }
    }
}
