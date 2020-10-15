<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;

class AddUserForm
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max=30,
     *     maxMessage="form.add_user.name.max_message"
     * )
     */
    private ?string $name = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max=50,
     *     maxMessage="form.add_user.surname.max_message"
     * )
     */
    private ?string $surname = null;

    private ?int $province = null;
    private ?int $city = null;

    /**
     * @Assert\Length(
     *     max=10000,
     *     maxMessage="form.add_user.description.max_message"
     * )
     */
    private ?string $description = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
