<?php

namespace Customize\Twig\Extension;

use Customize\Service\DiscountService;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DiscountExtension extends AbstractExtension
{
    private $security;
    private $discountService;

    public function __construct(Security $security, DiscountService $discountService)
    {
        $this->security = $security;
        $this->discountService = $discountService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('discount_price', [$this, 'getDiscountPrice']),
            new TwigFunction('discount_rate', [$this, 'getDiscountRate']),
            new TwigFunction('rank_label', [$this, 'getRankLabel']),
        ];
    }

    public function getDiscountPrice($Product): int
    {
        return $this->discountService->getDiscountedPrice($Product, $this->security->getUser());
    }

    public function getDiscountRate(): float
    {
        return $this->discountService->getDiscountRate($this->security->getUser());
    }

    public function getRankLabel(): string
    {
        return $this->discountService->getRankLabel($this->security->getUser());
    }
}
