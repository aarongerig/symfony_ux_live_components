<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Enum\PremiumType;
use App\Service\PremiumCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Metadata\UrlMapping;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
class LiveCalculator extends AbstractController
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[ExposeInTemplate('insurance_value')]
    #[LiveProp(writable: true, url: new UrlMapping(as: 'value'))]
    public ?int $insuranceValue = null;

    public function __construct(private readonly PremiumCalculatorService $calculator) {}

    public function calculate(PremiumType $type): array
    {
        return $this->calculator->calculate($this->insuranceValue, $type);
    }
}
