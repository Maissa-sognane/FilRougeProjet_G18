<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NiveauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 */
class Niveau
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"competence_read", "competence_write", "referentiel_read", "ref_grpecompetence_read", "brief:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competence_read", "competence_write", "referentiel_read", "get", "ref_grpecompetence_read", "brief:read", "briefpromogroupe:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Groups({"competence_read", "competence_write", "referentiel_read", "get", "ref_grpecompetence_read", "brief:read", "briefpromogroupe:read"})
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="text")
     * @Groups({"competence_read", "competence_write", "referentiel_read", "get", "ref_grpecompetence_read", "brief:read", "briefpromogroupe:read"})
     */
    private $groupeAction;

    /**
     * @ORM\ManyToOne(targetEntity=Competences::class, inversedBy="niveau")
     * @Groups({"brief:read", "briefpromogroupe:read"})
     */
    private $competences;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competence_read", "competence_write"})
     */
    private $isdeleted;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="niveau")
     */
    private $brief;

    /**
     * @ORM\ManyToMany(targetEntity=LivrablePartiel::class, mappedBy="niveau")
     */
    private $livrablePartiels;

    public function __construct()
    {
        $this->livrablePartiels = new ArrayCollection();
    }

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

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    public function getGroupeAction(): ?string
    {
        return $this->groupeAction;
    }

    public function setGroupeAction(string $groupeAction): self
    {
        $this->groupeAction = $groupeAction;

        return $this;
    }

    public function getCompetences(): ?Competences
    {
        return $this->competences;
    }

    public function setCompetences(?Competences $competences): self
    {
        $this->competences = $competences;

        return $this;
    }

    public function getIsdeleted(): ?bool
    {
        return $this->isdeleted;
    }

    public function setIsdeleted(bool $isdeleted): self
    {
        $this->isdeleted = $isdeleted;

        return $this;
    }

    public function getBrief(): ?Brief
    {
        return $this->brief;
    }

    public function setBrief(?Brief $brief): self
    {
        $this->brief = $brief;

        return $this;
    }

    /**
     * @return Collection|LivrablePartiel[]
     */
    public function getLivrablePartiels(): Collection
    {
        return $this->livrablePartiels;
    }

    public function addLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if (!$this->livrablePartiels->contains($livrablePartiel)) {
            $this->livrablePartiels[] = $livrablePartiel;
            $livrablePartiel->addNiveau($this);
        }

        return $this;
    }

    public function removeLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if ($this->livrablePartiels->contains($livrablePartiel)) {
            $this->livrablePartiels->removeElement($livrablePartiel);
            $livrablePartiel->removeNiveau($this);
        }

        return $this;
    }
}
