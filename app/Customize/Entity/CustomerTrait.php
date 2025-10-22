<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;

trait CustomerTrait
{
    /**
     * @var int|null
     *
     * @ORM\Column(name="rank_id", type="integer", nullable=false, options={"default": 1})
     */
    private $rank_id = 1;

    public function getRankId(): ?int
    {
        return $this->rank_id;
    }

    public function setRankId(?int $rank_id): self
    {
        $this->rank_id = $rank_id;
        return $this;
    }
}
