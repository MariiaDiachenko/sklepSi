<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShopRepository")
 */
class Shop
{
    const NUMBER_OF_ITEMS = 10;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank
     * @Assert\Regex("/^[\p{L}_\d \n]+$/")
     * @Assert\Length(min=1, max=80)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex("/^[\p{L}_\d \n]+$/")
     * @Assert\Length(min=1, max=80)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex("/^\+?[\d]+$/")
     * @Assert\Length(min=9, max=18)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(checkMX = true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="shop", fetch="LAZY")
     */
    private $products;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Disposal", mappedBy="shop", cascade={"persist", "remove"})
     */
    private $disposal;


    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function hasProducts()
    {
      return (bool) count($this->products);
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setShop($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getShop() === $this) {
                $product->setShop(null);
            }
        }

        return $this;
    }

    public function getDisposal(): ?Disposal
    {
        return $this->disposal;
    }

    public function setDisposal(?Disposal $disposal): self
    {
        $this->disposal = $disposal;

        // set (or unset) the owning side of the relation if necessary
        $newShop = $disposal === null ? null : $this;
        if ($newShop !== $disposal->getShop()) {
            $disposal->setShop($newShop);
        }

        return $this;
    }
}
