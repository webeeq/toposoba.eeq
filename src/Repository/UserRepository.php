<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    private string $date;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->date = date('Y-m-d H:i:s');
    }

    public function getUserList(int $level, int $listLimit): array
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT u FROM App:User u
            LEFT JOIN App:Province p WITH u.province = p.id
            LEFT JOIN App:City c WITH u.city = c.id
            WHERE u.active = 1 AND u.date <= :date
            ORDER BY u.number DESC, u.date ASC'
        )
            ->setParameter('date', $this->date)
            ->setFirstResult(($level - 1) * $listLimit)
            ->setMaxResults($listLimit);

        return $query->getResult();
    }

    public function getUserCount(): int
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT COUNT(u.id) AS total FROM App:User u
            LEFT JOIN App:Province p WITH u.province = p.id
            LEFT JOIN App:City c WITH u.city = c.id
            WHERE u.active = 1 AND u.date <= :date'
        )->setParameter('date', $this->date);

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
        $provinceId = ($province >= 1) ? ' AND p.id = :province'
            : ' AND :province = :province';
        $cityId = ($city >= 1) ? ' AND c.id = :city' : ' AND :city = :city';
        $userName = ($name !== '') ? ' AND u.name LIKE :name'
            : ' AND :name = :name';
        $userSurname = ($surname !== '') ? ' AND u.surname LIKE :surname'
            : ' AND :surname = :surname';

        $query = $this->getEntityManager()->createQuery(
            'SELECT u FROM App:User u
            LEFT JOIN App:Province p WITH u.province = p.id
            LEFT JOIN App:City c WITH u.city = c.id
            WHERE u.active = 1 AND u.date <= :date'
                . $provinceId . $cityId . $userName . $userSurname . '
            ORDER BY u.number DESC, u.date ASC'
        )
            ->setParameter('date', $this->date)
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
        $provinceId = ($province >= 1) ? ' AND p.id = :province'
            : ' AND :province = :province';
        $cityId = ($city >= 1) ? ' AND c.id = :city' : ' AND :city = :city';
        $userName = ($name !== '') ? ' AND u.name LIKE :name'
            : ' AND :name = :name';
        $userSurname = ($surname !== '') ? ' AND u.surname LIKE :surname'
            : ' AND :surname = :surname';

        $query = $this->getEntityManager()->createQuery(
            'SELECT COUNT(u.id) AS total FROM App:User u
            LEFT JOIN App:Province p WITH u.province = p.id
            LEFT JOIN App:City c WITH u.city = c.id
            WHERE u.active = 1 AND u.date <= :date'
                . $provinceId . $cityId . $userName . $userSurname
        )
            ->setParameter('date', $this->date)
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
            'SELECT u FROM App:User u
            LEFT JOIN App:Province p WITH u.province = p.id
            LEFT JOIN App:City c WITH u.city = c.id
            WHERE u.active = 1 AND u.date <= :date
            ORDER BY RAND()'
        )
            ->setParameter('date', $this->date)
            ->setFirstResult(0)
            ->setMaxResults($listLimit);

        return $query->getResult();
    }

    public function getUserData(int $user): ?User
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT u FROM App:User u
            LEFT JOIN App:Province p WITH u.province = p.id
            LEFT JOIN App:City c WITH u.city = c.id
            WHERE u.active = 1 AND u.date <= :date AND u.id = :user'
        )
            ->setParameter('date', $this->date)
            ->setParameter('user', $user);

        return $query->getOneOrNullResult();
    }

    public function isUserData(int $user): bool
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT u.id FROM App:User u
            WHERE u.active = 1 AND u.date <= :date AND u.id = :user'
        )
            ->setParameter('date', $this->date)
            ->setParameter('user', $user);

        return (bool) $query->getOneOrNullResult();
    }

    public function updateUserNumber(int $user): ?int
    {
        $query = $this->getEntityManager()->createQuery(
            'UPDATE App:User u SET u.number = (u.number + 1)
            WHERE u.active = 1 AND u.date <= :date AND u.id = :user'
        )
            ->setParameter('date', $this->date)
            ->setParameter('user', $user);

        return $query->getOneOrNullResult();
    }

    public function updateUserRanking(int $user): ?int
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT MAX(u.number) AS max FROM App:User u
            WHERE u.active = 1 AND u.date <= :date'
        )->setParameter('date', $this->date);

        try {
            $max = (int) $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $max = 0;
        }

        $query = $this->getEntityManager()->createQuery(
            'UPDATE App:User u SET u.ranking = (:max / u.number)
            WHERE u.active = 1 AND u.date <= :date AND u.id = :user'
        )
            ->setParameter('max', $max)
            ->setParameter('date', $this->date)
            ->setParameter('user', $user);

        return $query->getOneOrNullResult();
    }
}
