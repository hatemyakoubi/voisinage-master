<?php

namespace App\Entity;

use Serializable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserImageRepository;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @ORM\Entity(repositoryClass=UserImageRepository::class)
 * @Vich\Uploadable
 */
class UserImage implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="users_images", fileNameProperty="imageName")
     * 
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string")
     *
     * @var string|null
     */
    private $imageName ;

    /**
     * @ORM\Column(type="datetime") 
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="userImage", cascade={"persist", "remove"})
     */
    private $userImages;

    
     private $data;
    
   public function __construct(){
        $this->updatedAt= new \DateTime();
    }
    
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

       if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();
        }
    }

   
    public function getId(): ?int
        {
            return $this->id;
        }
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
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

     public function getUserImages(): ?User
     {
         return $this->userImages;
     }

     public function setUserImages(?User $userImages): self
     {
         $this->userImages = $userImages;

         return $this;
     }

    public function serialize() {
        return serialize($this->data);
    }
    public function unserialize($data) {
        $this->data = unserialize($data);
    }
   


    
    
    
}
