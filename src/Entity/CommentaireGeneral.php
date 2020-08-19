<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommentaireGeneralRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CommentaireGeneralRepository::class)
 */
class CommentaireGeneral
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $piecejointe;

    /**
     * @ORM\ManyToOne(targetEntity=FileDeDiscussion::class, inversedBy="commentaireGenerals")
     */
    private $filedediscussion;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentaireGenerals")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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

    public function getPiecejointe(): ?string
    {
        return $this->piecejointe;
    }

    public function setPiecejointe(?string $piecejointe): self
    {
        $this->piecejointe = $piecejointe;

        return $this;
    }

    public function getFiledediscussion(): ?FileDeDiscussion
    {
        return $this->filedediscussion;
    }

    public function setFiledediscussion(?FileDeDiscussion $filedediscussion): self
    {
        $this->filedediscussion = $filedediscussion;

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
}
