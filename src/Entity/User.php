<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="user",
 *     indexes={
 *         @ORM\Index(name="name", columns={"name"}),
 *         @ORM\Index(name="surname", columns={"surname"}),
 *         @ORM\Index(name="ranking", columns={"ranking"}),
 *         @ORM\Index(name="number", columns={"number"}),
 *         @ORM\Index(name="date", columns={"date"})
 *     }
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $active;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private ?string $surname;

    /**
     * @ORM\Column(type="text", length=65535)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private ?int $ranking;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private ?int $number;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private ?string $ip;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $date;

    /**
     * @ORM\ManyToOne(targetEntity="Province", inversedBy="users")
     * @ORM\JoinColumn(name="province_id", referencedColumnName="id")
     */
    private ?Province $province;

    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="users")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private ?City $city;

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
