<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="email_idx",
 *              columns={"email"},
 *          )
 *     }
 * )
 *
 * @UniqueEntity(fields={"email", "username"})
 */
class User implements UserInterface
{
    /*
    * Number of items per page
    */
    const NUMBER_OF_ITEMS = 10;

    /**
     * Role user.
     *
     * @var string
     */
    const ROLE_USER = 'ROLE_USER';

    /**
     * Role admin.
     *
     * @var string
     */
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     *
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Regex("/^[\p{L}\d ]+$/", groups={"registration"})
     * @Assert\Length(min=1, max=80, groups={"registration"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=80)
     *
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Regex("/^[\p{L}_\d ]+$/", groups={"registration"})
     * @Assert\Length(min=1, max=80, groups={"registration"})
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=80)
     *
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Regex("/^[\p{L}_\d ]+$/", groups={"registration"})
     * @Assert\Length(min=1, max=254, groups={"registration"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Length(
     *     min="3",
     *     max="255",
     *    groups={"registration"}
     * )
     *
     * @SecurityAssert\UserPassword
     */
    private $password;

    /**
     * @ORM\Column(
     *     type="string",
     *     length=128
     * )
     *
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Email(groups={"registration"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     *
     * @Assert\Regex("/^\+?[\d]+$/", groups={"registration"})
     * @Assert\Length(min=9, max=18, groups={"registration"})
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Role", mappedBy="user", orphanRemoval=true)
     */
    private $roles;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Disposal", mappedBy="user")
     */
    private $disposals;

    /**
    * User Entity constructor
    */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->disposals = new ArrayCollection();
    }

    /**
     * @see UserInterface
     *
     * @inheritdoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @see UserInterface
     *
     * @inheritdoc
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
    * @return int|null
    */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
    * @return string|null
    */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
    * @param string $name
    *
    * @return User
    */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
    * @return string|null
    */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
    * @param string $surname
    *
    * @return User
    */
    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
    * @return string|null
    */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
    * @param string $username
    *
    * @return User
    */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
    * @return string|null
    */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
    * @param string $password
    *
    * @return User
    */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
    * @return string
    */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
    * @param string $email
    *
    * @return User
    */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
    * @return string|null
    */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
    * @param string $phone
    *
    * @return User
    */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Role[]
     */
    public function getRoles()
    {
        $roles = [];
        foreach ($this->roles as $role) {
            $roles[] = $role->getRole();
        }
        $roles[] = static::ROLE_USER;

        return array_unique($roles);
    }

    /**
    * @param Role $role
    *
    * @return User
    */
    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->setUser($this);
        }

        return $this;
    }

    /**
    * @param Role $role
    *
    * @return User
    */
    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
            // set the owning side to null (unless already changed)
            if ($role->getUser() === $this) {
                $role->setUser(null);
            }
        }

        return $this;
    }

    /**
    * @return \DateTimeInterface|null
    */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
    * @param \DateTimeInterface $createdAt
    *
    * @return User
    */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
    * @return \DateTimeInterface|null
    */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
    * @param \DateTimeInterface $updatedAt
    *
    * @return User
    */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Disposal[]
     */
    public function getDisposals(): Collection
    {
        return $this->disposals;
    }

    /**
    * @param Disposal $disposal
    *
    * @return User
    */
    public function addDisposal(Disposal $disposal): self
    {
        if (!$this->disposals->contains($disposal)) {
            $this->disposals[] = $disposal;
            $disposal->setUser($this);
        }

        return $this;
    }

    /**
    * @param Disposal $disposal
    *
    * @return User
    */
    public function removeDisposal(Disposal $disposal): self
    {
        if ($this->disposals->contains($disposal)) {
            $this->disposals->removeElement($disposal);
            // set the owning side to null (unless already changed)
            if ($disposal->getUser() === $this) {
                $disposal->setUser(null);
            }
        }

        return $this;
    }
}
