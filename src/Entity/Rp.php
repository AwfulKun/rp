<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rp
 *
 * @ORM\Table(name="rp", indexes={@ORM\Index(name="fk_rp_status1_idx", columns={"status_id"}), @ORM\Index(name="fk_rp_app_user1_idx", columns={"app_user_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\RpRepository")
 */
class Rp
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    private $link;

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
     * @var Status
     *
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     * })
     */
    private $status;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppCharacter", inversedBy="rp")
     * @ORM\JoinTable(name="rp_has_app_character",
     *   joinColumns={
     *     @ORM\JoinColumn(name="rp_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="app_character_id", referencedColumnName="id")
     *   }
     * )
     */
    private $appCharacter;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->appCharacter = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

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

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|AppCharacter[]
     */
    public function getAppCharacter(): Collection
    {
        return $this->appCharacter;
    }

    public function addAppCharacter(AppCharacter $appCharacter): self
    {
        if (!$this->appCharacter->contains($appCharacter)) {
            $this->appCharacter[] = $appCharacter;
        }

        return $this;
    }

    public function removeAppCharacter(AppCharacter $appCharacter): self
    {
        if ($this->appCharacter->contains($appCharacter)) {
            $this->appCharacter->removeElement($appCharacter);
        }

        return $this;
    }

}
