<?php

declare(strict_types=1);

namespace App\Form;

class UserSearchForm
{
    private ?string $name = null;
    private ?string $surname = null;
    private ?int $province = null;
    private ?int $city = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getProvince(): ?int
    {
        return $this->province;
    }

    public function setProvince(int $province): void
    {
        $this->province = $province;
    }

    public function getCity(): ?int
    {
        return $this->city;
    }

    public function setCity(int $city): void
    {
        $this->city = $city;
    }
}
