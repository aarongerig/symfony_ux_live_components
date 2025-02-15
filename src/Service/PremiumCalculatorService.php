<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\PremiumType;

class PremiumCalculatorService
{
    private ?int $insuranceValue = null;
    private PremiumType $type;

    public function calculate(?int $insuranceValue, PremiumType $type): array
    {
        $this->insuranceValue = $insuranceValue;
        $this->type = $type;

        return [
            'base_value' => $this->getBaseValue(),
            'insurance_premium' => $this->getInsurancePremium(),
            'stamp_tax' => $this->getStampTax(),
            'subtotal' => $this->getSubtotal(),
            'prevention_fee' => $this->getPreventionFee(),
            'annual_premium' => $this->getAnnualPremium(),
        ];
    }

    /**
     * Returns the base value for the insurance premium calculation.
     * Buildings are insured for 2/3 of their value during construction, e.g. CHF 1'000'000 -> CHF 666'667
     */
    private function getBaseValue(): float
    {
        $baseValue = $this->insuranceValue ?? 0;

        if ($this->type === PremiumType::CONSTRUCTION) {
            $baseValue = \ceil($baseValue * 2 / 3);
        }

        return $baseValue;
    }

    /**
     * Returns the insurance premium for the given base value.
     * The premium is calculated as 0.4‰ of the base value, e.g. CHF 1'000'000 -> CHF 400
     */
    private function getInsurancePremium(): float
    {
        return $this->getBaseValue() / 1000 * 0.4;
    }

    /**
     * Returns the stamp tax for the insurance premium, collected by the Swiss government.
     * The stamp tax is 5% of the insurance premium, e.g. CHF 400 -> CHF 20
     */
    private function getStampTax(): float
    {
        return $this->getInsurancePremium() / 100 * 5;
    }

    /**
     * Returns the subtotal of the insurance premium.
     * Consists of the insurance premium and the stamp tax, e.g. CHF 400 + CHF 20 = CHF 420
     */
    private function getSubtotal(): float
    {
        return $this->getInsurancePremium() + $this->getStampTax();
    }

    /**
     * Returns the prevention fee for the insurance premium.
     * The prevention fee is 0.15‰ of the base value, e.g. CHF 1'000'000 -> CHF 150
     */
    private function getPreventionFee(): float
    {
        return $this->getBaseValue() / 1000 * 0.15;
    }

    /**
     * Returns the annual premium for the insurance value.
     * Consists of the subtotal and the prevention fee, e.g. CHF 420 + CHF 150 = CHF 570
     */
    private function getAnnualPremium(): float
    {
        return $this->getSubtotal() + $this->getPreventionFee();
    }
}
