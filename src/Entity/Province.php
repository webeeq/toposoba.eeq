<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProvinceRepository;
use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="province",
 *     indexes={@ORM\Index(name="name", columns={"name"})}
 * )
 * @ORM\Entity(repositoryClass=ProvinceRepository::class)
 */
class Province
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="smallint", options={"unsigned":true})
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
     * @ORM\OneToMany(targetEntity="City", mappedBy="province")
     */
    private Collection $cities;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="province")
     */
    private Collection $users;

    public function __construct()
    {
        $this->cities = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

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

    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(City $city): self
    {
        if (!$this->cities->contains($city)) {
            $this->cities[] = $city;
            $city->setProvince($this);
        }

        return $this;
    }

    public function removeCity(City $city): self
    {
        if ($this->cities->contains($city)) {
            $this->cities->removeElement($city);
            if ($city->getProvince() === $this) {
                $city->setProvince(null);
            }
        }

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProvince($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            if ($user->getProvince() === $this) {
                $user->setProvince(null);
            }
        }

        return $this;
    }
}
