<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DisposalRepository")
 */
class Disposal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DisposalDetails", mappedBy="disposal" cascade={"remove"})
     */
    private $disposal_details;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Shop", inversedBy="disposal", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;

    public function __construct()
    {
        $this->disposal_details = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return Collection|DisposalDetails[]
     */
    public function getDisposalDetails(): Collection
    {
        return $this->disposal_details;
    }

    public function addDisposalDetail(DisposalDetails $disposalDetail): self
    {
        if (!$this->disposal_details->contains($disposalDetail)) {
            $this->disposal_details[] = $disposalDetail;
            $disposalDetail->setDisposal($this);
        }

        return $this;
    }

    public function removeDisposalDetail(DisposalDetails $disposalDetail): self
    {
        if ($this->disposal_details->contains($disposalDetail)) {
            $this->disposal_details->removeElement($disposalDetail);
            // set the owning side to null (unless already changed)
            if ($disposalDetail->getDisposal() === $this) {
                $disposalDetail->setDisposal(null);
            }
        }

        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(Shop $shop): self
    {
        $this->shop = $shop;

        return $this;
    }
}
