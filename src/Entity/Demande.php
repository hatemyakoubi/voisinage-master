<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DemandeRepository::class)
 */
class Demande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

       /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $budget;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="demandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $publier;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity=SousCategorie::class, inversedBy="demandes")
     */
    private $souscategorie;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="demandes", orphanRemoval=true)
     */
    private $comments;

    public function __construct(){
        $this->createdAt = new \DateTime();
        $this->publier = false;
        $this->comments = new ArrayCollection();
    

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

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(float $budget): self
    {
        $this->budget = $budget;

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

    public function getPublier(): ?bool
    {
        return $this->publier;
    }

    public function setPublier(bool $publier): self
    {
        $this->publier = $publier;

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


    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

   public function __toString()
   {
      return $this->titre; 
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
    * @return Collection|Comments[]
    */
   public function getComments(): Collection
   {
       return $this->comments;
   }

   public function addComment(Comments $comment): self
   {
       if (!$this->comments->contains($comment)) {
           $this->comments[] = $comment;
           $comment->setDemandes($this);
       }

       return $this;
   }

   public function removeComment(Comments $comment): self
   {
       if ($this->comments->removeElement($comment)) {
           // set the owning side to null (unless already changed)
           if ($comment->getDemandes() === $this) {
               $comment->setDemandes(null);
           }
       }

       return $this;
   }

}
