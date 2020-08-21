<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StatistiquesCompetencesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=StatistiquesCompetencesRepository::class)
 */
class StatistiquesCompetences
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
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="statistiquesCompetences")
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="statistiquesCompetences")
     */
    private $referentiel;

    /**
     * @ORM\ManyToOne(targetEntity=Competences::class, inversedBy="statistiquesCompetences")
     */
    private $competence;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="statistiquesCompetences")
     */
    private $promo;

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

    public function getApprenant(): ?Apprenant
    {
        return $this->apprenant;
    }

    public function setApprenant(?Apprenant $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

        return $this;
    }

    public function getCompetence(): ?Competences
    {
        return $this->competence;
    }

    public function setCompetence(?Competences $competence): self
    {
        $this->competence = $competence;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }
}
