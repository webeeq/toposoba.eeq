<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function getCityList(int $province): array
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT c FROM App\Entity\City c
            WHERE c.province = :province
            ORDER BY c.name ASC'
        )->setParameter('province', $province);

        return $query->getResult();
    }
}
