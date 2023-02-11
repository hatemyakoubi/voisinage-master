<?php

namespace App\Entity;

use App\Repository\SousCategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SousCategorieRepository::class)
 */
class SousCategorie
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
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="souscategories")
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=Demande::class, mappedBy="souscategorie")
     */
    private $demandes;

    /**
     * @ORM\OneToMany(targetEntity=Offre::class, mappedBy="souscategorie")
     */
    private $offres;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
        $this->offres = new ArrayCollection();
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
  
     public function getCategorie(): ?Categorie
     {
         return $this->categorie;
     }

     public function setCategorie(?Categorie $categorie): self
     {
         $this->categorie = $categorie;

         return $this;
     }
    
  
    public function __toString(){
        return $this->title;
    }

    /**
     * @return Collection|Demande[]
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): self
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes[] = $demande;
            $demande->setSouscategorie($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): self
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getSouscategorie() === $this) {
                $demande->setSouscategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Offre[]
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setSouscategorie($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getSouscategorie() === $this) {
                $offre->setSouscategorie(null);
            }
        }

        return $this;
    }


}
