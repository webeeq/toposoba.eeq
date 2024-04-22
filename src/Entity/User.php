<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\{City, Province};
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Index(name: 'name', columns: ['name'])]
#[ORM\Index(name: 'surname', columns: ['surname'])]
#[ORM\Index(name: 'ranking', columns: ['ranking'])]
#[ORM\Index(name: 'number', columns: ['number'])]
#[ORM\Index(name: 'date', columns: ['date'])]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(length: 30)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $surname = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $ranking = null;

    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $number = null;

    #[ORM\Column(length: 15)]
    private ?string $ip = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: Province::class, inversedBy: 'users')]
    #[ORM\JoinColumn(name: 'province_id', referencedColumnName: 'id')]
    private ?Province $province = null;

    #[ORM\ManyToOne(targetEntity: City::class, inversedBy: 'users')]
    #[ORM\JoinColumn(name: 'city_id', referencedColumnName: 'id')]
    private ?City $city = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRanking(): ?int
    {
        return $this->ranking;
    }

    public function setRanking(int $ranking): self
    {
        $this->ranking = $ranking;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getProvince(): ?Province
    {
        return $this->province;
    }

    public function setProvince(?Province $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }
}
