<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Province;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProvinceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Province::class);
    }

    public function getProvinceList(): array
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT p FROM App:Province p
            WHERE p.active = 1
            ORDER BY p.name ASC'
        );

        return $query->getResult();
    }
}
