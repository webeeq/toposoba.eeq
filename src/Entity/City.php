<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="city",
 *     indexes={@ORM\Index(name="name", columns={"name"})}
 * )
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
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
     * @ORM\ManyToOne(targetEntity="Province", inversedBy="cities")
     * @ORM\JoinColumn(name="province_id", referencedColumnName="id")
     */
    private ?Province $province;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="city")
     */
    private Collection $users;

    public function __construct()
    {
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

    public function getProvince(): ?Province
    {
        return $this->province;
    }

    public function setProvince(?Province $province): self
    {
        $this->province = $province;

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
            $user->setCity($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            if ($user->getCity() === $this) {
                $user->setCity(null);
            }
        }

        return $this;
    }
}
