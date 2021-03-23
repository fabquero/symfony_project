<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @Vich\Uploadable
 */
class Post
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
    private $sujet;

    /**
     * @ORM\Column(type="text")
     */
    private $texte;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $NbVoteGauche;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $NbVoteDroite;

    /**
     * @ORM\OneToMany(targetEntity=Etoile::class, mappedBy="post")
     */
    private $etoiles;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="post")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $NomImage;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="articles", fileNameProperty="NomImage")
     *
     * @var File|null
     */
    private $imageFichier;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    public function __construct()
    {
        $this->etoiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getNbVoteGauche(): ?int
    {
        return $this->NbVoteGauche;
    }

    public function setNbVoteGauche(?int $NbVoteGauche): self
    {
        $this->NbVoteGauche = $NbVoteGauche;

        return $this;
    }

    public function getNbVoteDroite(): ?int
    {
        return $this->NbVoteDroite;
    }

    public function setNbVoteDroite(?int $NbVoteDroite): self
    {
        $this->NbVoteDroite = $NbVoteDroite;

        return $this;
    }

    /**
     * @return Collection|Etoile[]
     */
    public function getEtoiles(): Collection
    {
        return $this->etoiles;
    }

    public function addEtoile(Etoile $etoile): self
    {
        if (!$this->etoiles->contains($etoile)) {
            $this->etoiles[] = $etoile;
            $etoile->setPost($this);
        }

        return $this;
    }

    public function removeEtoile(Etoile $etoile): self
    {
        if ($this->etoiles->removeElement($etoile)) {
            // set the owning side to null (unless already changed)
            if ($etoile->getPost() === $this) {
                $etoile->setPost(null);
            }
        }

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNomImage(): ?string
    {
        return $this->NomImage;
    }

    public function setNomImage(?string $NomImage): self
    {
        $this->NomImage = $NomImage;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFichier(): ?File
    {
        return $this->imageFichier;
    }

    /**
     * @param File|null $imageFichier
     */
    public function setImageFichier(?File $imageFichier): void
    {
        $this->imageFichier = $imageFichier;
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


}
