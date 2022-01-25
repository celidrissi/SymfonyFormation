<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Api;

/**
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Api\Groups("restaurant_list")
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=6)
     * @Api\Groups("restaurant_list")
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=80)
     * @Api\Groups("restaurant_list")
     */
    private $city;

    /**
     * @ORM\OneToOne(targetEntity=Restaurant::class, inversedBy="address", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Api\Groups("restaurant_list")
     */
    private $restaurant;

    public function __construct($id, $street, $zipcode, $city)
    {
        $this->id = $id;
        $this->street = $street;
        $this->zipcode = $zipcode;
        $this->city = $city;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

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

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function __toString()
    {
        return urlencode($this->street.', '.$this->zipcode.', '.$this->city);
    }
}
