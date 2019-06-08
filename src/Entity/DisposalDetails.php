<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DisposalDetailsRepository")
 */
class DisposalDetails
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $copiedPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Disposal", inversedBy="disposal_details")
     */
    private $disposal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $product_name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCopiedPrice(): ?int
    {
        return $this->copiedPrice;
    }

    public function setCopiedPrice(int $copiedPrice): self
    {
        $this->copiedPrice = $copiedPrice;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getDisposal(): ?Disposal
    {
        return $this->disposal;
    }

    public function setDisposal(?Disposal $disposal): self
    {
        $this->disposal = $disposal;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    public function setProductName(string $product_name): self
    {
        $this->product_name = $product_name;

        return $this;
    }
}
