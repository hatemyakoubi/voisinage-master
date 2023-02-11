<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OffreRepository::class)
 */
class Offre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $publier;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="offres")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=SousCategorie::class, inversedBy="offres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $souscategorie;

    /**
     * @ORM\OneToMany(targetEntity=CommentOffre::class, mappedBy="offres")
     */
    private $commentOffres;

    /**
     * @ORM\Column(type="text")
     */
    private $titre;

    /**
     * @ORM\OneToMany(targetEntity=Images::class, mappedBy="offres", orphanRemoval=true ,cascade={"persist"})
     */
    private $images;

    
    public function __construct(){
        $this->createdAt = new \DateTime();
        $this->publier = false;
        $this->commentOffres = new ArrayCollection();
        $this->images = new ArrayCollection();
     
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getPublier(): ?bool
    {
        return $this->publier;
    }

    public function setPublier(bool $publier): self
    {
        $this->publier = $publier;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSouscategorie(): ?SousCategorie
    {
        return $this->souscategorie;
    }

    public function setSouscategorie(?SousCategorie $souscategorie): self
    {
        $this->souscategorie = $souscategorie;

        return $this;
    }

    /**
     * @return Collection|CommentOffre[]
     */
    public function getCommentOffres(): Collection
    {
        return $this->commentOffres;
    }

    public function addCommentOffre(CommentOffre $commentOffre): self
    {
        if (!$this->commentOffres->contains($commentOffre)) {
            $this->commentOffres[] = $commentOffre;
            $commentOffre->setOffres($this);
        }

        return $this;
    }

    public function removeCommentOffre(CommentOffre $commentOffre): self
    {
        if ($this->commentOffres->removeElement($commentOffre)) {
            // set the owning side to null (unless already changed)
            if ($commentOffre->getOffres() === $this) {
                $commentOffre->setOffres(null);
            }
        }

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setOffres($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getOffres() === $this) {
                $image->setOffres(null);
            }
        }

        return $this;
    }

}
