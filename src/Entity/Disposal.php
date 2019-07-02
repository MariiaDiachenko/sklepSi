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
    const NUMBER_OF_ITEMS = 10;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Regex("/^[\p{L}_\d \n]+$/")
     * @Assert\Length(min=1, max=80)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DisposalDetails", mappedBy="disposal", cascade={"persist", "remove"})
     */
    private $disposalDetails;

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

    /**
     * class constructor
     */
    public function __construct()
    {
        $this->disposalDetails = new ArrayCollection();
    }

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
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     *
     * @param  string $address
     *
     * @return self
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     *
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     *
     * @param  string $status
     *
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     *
     * @return \DateTimeInterface|null
     */
    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    /**
     *
     * @param  DateTimeInterface $timestamp
     *
     * @return self
     */
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
        return $this->disposalDetails;
    }

    /**
     *
     * @param  DisposalDetails $disposalDetail
     *
     * @return self
     */
    public function addDisposalDetail(DisposalDetails $disposalDetail): self
    {
        if (!$this->disposalDetails->contains($disposalDetail)) {
            $this->disposalDetails[] = $disposalDetail;
            $disposalDetail->setDisposal($this);
        }

        return $this;
    }

    /**
     *
     * @param  DisposalDetails $disposalDetail
     *
     * @return self
     */
    public function removeDisposalDetail(DisposalDetails $disposalDetail): self
    {
        if ($this->disposalDetails->contains($disposalDetail)) {
            $this->disposalDetails->removeElement($disposalDetail);
            // set the owning side to null (unless already changed)
            if ($disposalDetail->getDisposal() === $this) {
                $disposalDetail->setDisposal(null);
            }
        }

        return $this;
    }

    /**
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     *
     * @param  User $user
     *
     * @return self
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     *
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     *
     * @param  DateTimeInterface $createdAt
     *
     * @return self
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     *
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     *
     * @param  DateTimeInterface $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
