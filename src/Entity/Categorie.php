<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=SousCategorie::class, mappedBy="categorie")
     */
    private $souscategories;


    public function __construct()
    {
        $this->souscategories = new ArrayCollection();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|SousCategorie[]
     */
    public function getSouscategories(): Collection
    {
        return $this->souscategories;
    }

    public function addSouscategory(SousCategorie $souscategory): self
    {
        if (!$this->souscategories->contains($souscategory)) {
            $this->souscategories[] = $souscategory;
            $souscategory->setCategorie($this);
        }

        return $this;
    }

    public function removeSouscategory(SousCategorie $souscategory): self
    {
        if ($this->souscategories->removeElement($souscategory)) {
            // set the owning side to null (unless already changed)
            if ($souscategory->getCategorie() === $this) {
                $souscategory->setCategorie(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }


   
}
