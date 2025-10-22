<?php

namespace Customize\Service;

use Doctrine\DBAL\Connection;
use Eccube\Entity\Product;
use Eccube\Entity\Customer;

class DiscountService
{
    private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function getDiscountedPrice(Product $product, ?Customer $customer): int
    {
        if (!$customer || !$customer->getRankId()) {
            return $product->getPrice02IncTaxMin();
        }

        $rank = $this->conn->fetchAssociative('SELECT * FROM mtb_customer_rank WHERE id = ?', [$customer->getRankId()]);
        if (!$rank) {
            return $product->getPrice02IncTaxMin();
        }

        $rate = (float)$rank['discount_rate'];
        $base = $product->getPrice02IncTaxMin();

        return (int)floor($base * (1 - $rate));
    }

    public function getRankLabel(?Customer $customer): string
    {
        if (!$customer || !$customer->getRankId()) return '一般会員';
        return $this->conn->fetchOne('SELECT name FROM mtb_customer_rank WHERE id = ?', [$customer->getRankId()]) ?? '一般会員';
    }

    public function getDiscountRate(?Customer $customer): float
    {
        if (!$customer || !$customer->getRankId()) return 0.0;
        return (float)($this->conn->fetchOne('SELECT discount_rate FROM mtb_customer_rank WHERE id = ?', [$customer->getRankId()]) ?? 0);
    }
}
