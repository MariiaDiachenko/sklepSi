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
     * @ORM\ManyToOne(targetEntity="App\Entity\Disposal", inversedBy="disposalDetails")
     */
    private $disposal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $productName;

    /**
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     *
     * @return int|null
     */
    public function getCopiedPrice(): ?int
    {
        return $this->copiedPrice;
    }

    /**
     *
     * @param  int $copiedPrice
     *
     * @return self
     */
    public function setCopiedPrice(int $copiedPrice): self
    {
        $this->copiedPrice = $copiedPrice;

        return $this;
    }

    /**
     *
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     *
     * @param  int $quantity
     *
     * @return self
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     *
     * @return Disposal|null
     */
    public function getDisposal(): ?Disposal
    {
        return $this->disposal;
    }

    /**
     *
     * @param  ?Disposal $disposal
     *
     * @return self
     */
    public function setDisposal(?Disposal $disposal): self
    {
        $this->disposal = $disposal;

        return $this;
    }

    /**
     *
     * @return string|null
     */
    public function getProductName(): ?string
    {
        return $this->productName;
    }

    /**
     *
     * @param  string $productName
     *
     * @return self
     */
    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }
}
