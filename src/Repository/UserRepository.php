<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUserList(int $level, int $listLimit): array
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT u FROM App\Entity\User u
            ORDER BY u.number DESC, u.date ASC'
        )
            ->setFirstResult(($level - 1) * $listLimit)
            ->setMaxResults($listLimit);

        return $query->getResult();
    }

    public function getUserCount(): int
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT COUNT(u.id) AS total FROM App\Entity\User u'
        );

        try {
            $count = (int) $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $count = 0;
        }

        return $count;
    }

    public function getSearchUserList(
        string $name,
        string $surname,
        int $province,
        int $city,
        int $level,
        int $listLimit
    ): array {
        $provinceId = ($province >= 1) ? 'u.province = :province'
            : ':province = :province';
        $cityId = ($city >= 1) ? ' AND u.city = :city' : ' AND :city = :city';
        $userName = ($name !== '') ? ' AND u.name LIKE :name'
            : ' AND :name = :name';
        $userSurname = ($surname !== '') ? ' AND u.surname LIKE :surname'
            : ' AND :surname = :surname';

        $query = $this->getEntityManager()->createQuery(
            'SELECT u FROM App\Entity\User u
            WHERE ' . $provinceId . $cityId . $userName . $userSurname . '
            ORDER BY u.number DESC, u.date ASC'
        )
            ->setParameter('province', $province)
            ->setParameter('city', $city)
            ->setParameter('name', '%' . $name . '%')
            ->setParameter('surname', '%' . $surname . '%')
            ->setFirstResult(($level - 1) * $listLimit)
            ->setMaxResults($listLimit);

        return $query->getResult();
    }

    public function getSearchUserCount(
        string $name,
        string $surname,
        int $province,
        int $city
    ): int {
        $provinceId = ($province >= 1) ? 'u.province = :province'
            : ':province = :province';
        $cityId = ($city >= 1) ? ' AND u.city = :city' : ' AND :city = :city';
        $userName = ($name !== '') ? ' AND u.name LIKE :name'
            : ' AND :name = :name';
        $userSurname = ($surname !== '') ? ' AND u.surname LIKE :surname'
            : ' AND :surname = :surname';

        $query = $this->getEntityManager()->createQuery(
            'SELECT COUNT(u.id) AS total FROM App\Entity\User u
            WHERE ' . $provinceId . $cityId . $userName . $userSurname
        )
            ->setParameter('province', $province)
            ->setParameter('city', $city)
            ->setParameter('name', '%' . $name . '%')
            ->setParameter('surname', '%' . $surname . '%');

        try {
            $count = (int) $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $count = 0;
        }

        return $count;
    }

    public function getRandomUserList(int $listLimit): array
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT u FROM App\Entity\User u ORDER BY RAND()'
        )
            ->setFirstResult(0)
            ->setMaxResults($listLimit);

        return $query->getResult();
    }

    public function getUserData(int $user): ?User
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT u FROM App\Entity\User u WHERE u.id = :user'
        )->setParameter('user', $user);

        return $query->getOneOrNullResult();
    }

    public function isUserData(int $user): bool
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT u.id FROM App\Entity\User u WHERE u.id = :user'
        )->setParameter('user', $user);

        return (bool) $query->getOneOrNullResult();
    }

    public function updateUserNumber(int $user): ?int
    {
        $query = $this->getEntityManager()->createQuery(
            'UPDATE App\Entity\User u
            SET u.number = (u.number + 1)
            WHERE u.id = :user'
        )->setParameter('user', $user);

        return $query->getOneOrNullResult();
    }

    public function updateUserRanking(int $user): ?int
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT MAX(u.number) AS max FROM App\Entity\User u'
        );

        try {
            $max = (int) $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $max = 0;
        }

        $query = $this->getEntityManager()->createQuery(
            'UPDATE App\Entity\User u
            SET u.ranking = (:max / u.number)
            WHERE u.id = :user'
        )
            ->setParameter('max', $max)
            ->setParameter('user', $user);

        return $query->getOneOrNullResult();
    }
}
