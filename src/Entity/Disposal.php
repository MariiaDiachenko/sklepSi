<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DisposalRepository")
 */
class Disposal
{
    const STATUS_WAITING_FOR_PAYMENT = 'WAITING_FOR_PAYMENT';
    const STATUS_PAYED = 'PAYED';
    const STATUS_SENDED = 'SENDED';

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
     * @ORM\OneToMany(targetEntity="App\Entity\DisposalDetails", mappedBy="disposal", cascade={"persist", "remove"})
     */
    private $disposal_details;

    /**
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $updatedAt;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="disposals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
