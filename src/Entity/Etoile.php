<?php

namespace App\Entity;

use App\Repository\EtoileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtoileRepository::class)
 */
class Etoile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $argument;

    /**
     * @ORM\Column(type="boolean")
     */
    private $gauche_droite;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="etoiles")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="etoiles")
     */
    private $utilisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArgument(): ?string
    {
        return $this->argument;
    }

    public function setArgument(?string $argument): self
    {
        $this->argument = $argument;

        return $this;
    }

    public function getGaucheDroite(): ?bool
    {
        return $this->gauche_droite;
    }

    public function setGaucheDroite(bool $gauche_droite): self
    {
        $this->gauche_droite = $gauche_droite;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

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
}
