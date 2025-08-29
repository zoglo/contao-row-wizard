<?php

declare(strict_types=1);

namespace Zoglo\SimpleColumnWizardBundle\EventListener;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\Asset\Packages;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener]
class AddAssetsListener
{
    public function __construct(
        private readonly ScopeMatcher $scopeMatcher,
        private readonly Packages $package,
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {
        if ($this->scopeMatcher->isBackendMainRequest($event))
        {
            //$GLOBALS['TL_JAVASCRIPT'][] = $this->package->getUrl('simple-column-wizard.js', 'zoglo_simple_column_wizard');
            $GLOBALS['TL_CSS'][] = $this->package->getUrl('simple-column-wizard.css', 'zoglo_simple_column_wizard');
        }
    }
}
