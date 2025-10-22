<?php

namespace Customize\Repository;

use Customize\Entity\MtbCustomerRank;
use Eccube\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class MtbCustomerRankRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MtbCustomerRank::class);
    }
}
