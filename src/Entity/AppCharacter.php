<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AppCharacter
 *
 * @ORM\Table(name="app_character")
 * @ORM\Entity
 */
class AppCharacter
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="surname", type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Rp", mappedBy="appCharacter")
     */
    private $rp;

    /**
     * @var AppUser
     *
     * @ORM\ManyToOne(targetEntity="AppUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="app_user_id", referencedColumnName="id")
     * })
     */
    private $appUser;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes = {"image/jpeg", "image/png", "image/gif", "image/jpg"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $owner;

    /**
     *  @var AppUser
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\AppUser")
     */
    private $owneralias;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rp = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return Collection|Rp[]
     */
    public function getRp(): Collection
    {
        return $this->rp;
    }

    public function addRp(Rp $rp): self
    {
        if (!$this->rp->contains($rp)) {
            $this->rp[] = $rp;
            $rp->addAppCharacter($this);
        }

        return $this;
    }

    public function removeRp(Rp $rp): self
    {
        if ($this->rp->contains($rp)) {
            $this->rp->removeElement($rp);
            $rp->removeAppCharacter($this);
        }

        return $this;
    }

    public function getAppUser(): ?AppUser
    {
        return $this->appUser;
    }

    public function setAppUser(?AppUser $appUser): self
    {
        $this->appUser = $appUser;

        return $this;
    }

    public function __toString() {
        return $this->name . ' ' . $this->surname;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(?string $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getOwneralias(): ?AppUser
    {
        return $this->owneralias;
    }

    public function setOwneralias(?AppUser $owneralias): self
    {
        $this->owneralias = $owneralias;

        return $this;
    }

}
