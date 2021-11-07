<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @ORM\Table(name="customers")
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $givenName;

    /**
     * @ORM\Column(type="text")
     */
    private $emailAddress;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $telephoneNumber;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $ccType;

    /**
     * @ORM\Column(type="string", length=100))
     */
    private $ccNumber;

    /**
     * @ORM\Column(type="integer")
     */
    private $cvv2;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $ccExpires;

    /**
     * @ORM\Column(type="text")
     */
    private $streetAddress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="integer")
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $countryFull;

    /**
     * @ORM\Column(type="float")
     */
    private $centimeters;

    /**
     * @ORM\Column(type="float")
     */
    private $kilograms;

    /**
     * @ORM\ManyToOne(targetEntity=Vehicule::class, inversedBy="customers", cascade={"persist"}))
     */
    private $Vehicule;

    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getGivenName(): ?string
    {
        return $this->givenName;
    }

    public function setGivenName(string $givenName): self
    {
        $this->givenName = $givenName;

        return $this;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): self
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(string $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getTelephoneNumber(): ?string
    {
        return $this->telephoneNumber;
    }

    public function setTelephoneNumber(string $telephoneNumber): self
    {
        $this->telephoneNumber = $telephoneNumber;

        return $this;
    }

    public function getCcType(): ?string
    {
        return $this->ccType;
    }

    public function setCcType(string $ccType): self
    {
        $this->ccType = $ccType;

        return $this;
    }

    public function getCcNumber(): ?string
    {
        return $this->ccNumber;
    }

    public function setCcNumber(string $ccNumber): self
    {
        $this->ccNumber = $ccNumber;

        return $this;
    }

    public function getCvv2(): ?int
    {
        return $this->cvv2;
    }

    public function setCvv2(int $cvv2): self
    {
        $this->cvv2 = $cvv2;

        return $this;
    }

    public function getCcExpires(): ?string
    {
        return $this->ccExpires;
    }

    public function setCcExpires(string $ccExpires): self
    {
        $this->ccExpires = $ccExpires;

        return $this;
    }

    public function getStreetAddress(): ?string
    {
        return $this->streetAddress;
    }

    public function setStreetAddress(string $streetAddress): self
    {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCountryFull(): ?string
    {
        return $this->countryFull;
    }

    public function setCountryFull(string $countryFull): self
    {
        $this->countryFull = $countryFull;

        return $this;
    }

    public function getCentimeters(): ?float
    {
        return $this->centimeters;
    }

    public function setCentimeters(float $centimeters): self
    {
        $this->centimeters = $centimeters;

        return $this;
    }

    public function getKilograms(): ?float
    {
        return $this->kilograms;
    }

    public function setKilograms(float $kilograms): self
    {
        $this->kilograms = $kilograms;

        return $this;
    }

    public function getVehicule(): ?Vehicule
    {
        return $this->Vehicule;
    }

    public function setVehicule(?Vehicule $Vehicule): self
    {
        $this->Vehicule = $Vehicule;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

}
