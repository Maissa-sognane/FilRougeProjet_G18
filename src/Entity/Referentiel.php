<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ApiResource(
 *      denormalizationContext={"groups"={"referentiel_write"}},
 *      collectionOperations={
 *    "get"={
 *       "path"="admin/referentiels",
 *       "normalization_context"={"groups":"referentiel_read"},
 *       "security"="is_granted('VIEW', object)",
 *       "security_message"="vous n'avez pas acces",
 *    },
 *     "postreferentiels"={
 *         "path"="admin/referentiels",
 *         "method"="post",
 *         "route_name"="createreferentiels",
 *         "security"="is_granted('VIEW', object)",
 *         "security_message"="vous n'avez pas acces",
 *     },
 *     "GetgrpecompetenceCompetences"={
 *          "method"="GET",
 *          "path"="admin/referentiels_grpecompetences",
 *          "route_name"="grpecompetenceCompetences",
 *          "normalization_context"={"groups":"referentiel_read"},
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces",
 *     },
 *     "api_referentiels_groupe_competences_get_subresource"={
 *          "method"="GET",
 *          "path"="admin/referentiels/grpecompetences",
 *          "security"="is_granted('VIEW', object)",
 *          "security_message"="vous n'avez pas acces",
 *     }
 *     },
 *     itemOperations={
 *      "postreferentielsbyid"={
 *         "path"="admin/referentiels/{id}",
 *         "method"="get",
 *         "route_name"="createreferentielsbyid",
 *         "normalization_context"={"groups":"referentiel_read"},
 *         "security"="is_granted('VIEW', object) or is_granted('VIEW_APPRENANT', object)",
 *         "security_message"="vous n'avez pas acces",
 *     },
 *     "api_referentiels_groupe_competences_get_subresource"={
 *        "method"="GET",
 *        "path"="admin/referentiels/{id}/grpecompetences",
 *        "normalization_context"={"groups":"ref_grpecompetence_read"},
 *        "security"="is_granted('VIEW', object)",
 *        "security_message"="vous n'avez pas acces",
 *     },
 *     "api_referentiels_groupe_competences_competences_get_subresource"={
 *               "method"="GET",
 *               "path"="admin/referentiels/{id}/grpecompetences/{groupeCompetence}",
 *               "normalization_context"={"groups":"ref_grpecompetence_read"},
 *               "security"="is_granted('VIEW', object) or is_granted('VIEW_APPRENANT', object)",
 *               "security_message"="vous n'avez pas acces",
 *     },
 *      "putreferentiel"={
 *         "method"="PUT",
 *         "path"="admin/referentiels/{id}",
 *         "route_name"="updateref",
 *         "security"="is_granted('VIEW', object) or is_granted('VIEW_APPRENANT', object)",
 *         "security_message"="vous n'avez pas acces",
 *     },
 *     }
 * )
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 */
class Referentiel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"referentiel_read", "groupe:read" ,"appreantgrpeprincipal:read", "promo_write", "promo_referentiel:read", "promoformateur:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"referentiel_read", "groupe:read" ,"promo:read", "appreantgrpeprincipal:read", "appreantattente:read", "promo_write", "promo_referentiel:read", "promoformateur:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Groups({ "referentiel_read", "groupe:read" ,"promo:read", "appreantgrpeprincipal:read", "appreantattente:read", "promo_referentiel:read", "promoformateur:read"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({ "referentiel_read","promo:read", "groupe:read" ,"appreantattente:read", "promo_referentiel:read", "promoformateur:read"})
     */
    private $programme;

    /**
     * @ORM\Column(type="string")
     * @Groups({"referentiel_read","promo:read", "groupe:read" ,"appreantattente:read", "promo_referentiel:read", "promoformateur:read"})
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="string")
     * @Groups({"referentiel_read","promo:read", "groupe:read" ,"appreantattente:read", "promo_referentiel:read", "promoformateur:read"})
     */
    private $critereAdmission;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetences::class, mappedBy="referentiels")
     * @Groups({"referentiel_read", "ref_grpecompetence_read", "promo_referentiel:read", "promoformateur:read"})
     * @ApiSubresource
     */
    private $groupeCompetences;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({ "referentiel_read"})
     */
    private $isdeleted;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="referentiel")
     */
    private $promos;

    /**
     * @ORM\OneToMany(targetEntity=Brief::class, mappedBy="referentiel")
     */
    private $briefs;

    /**
     * @ORM\OneToMany(targetEntity=StatistiquesCompetences::class, mappedBy="referentiel")
     */
    private $statistiquesCompetences;

    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->promos = new ArrayCollection();
        $this->briefs = new ArrayCollection();
        $this->statistiquesCompetences = new ArrayCollection();
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getProgramme(): ?string
    {
        return $this->programme;
    }

    public function setProgramme(string $programme): self
    {
        $this->programme = $programme;

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

    public function getCritereAdmission(): ?string
    {
        return $this->critereAdmission;
    }

    public function setCritereAdmission(string $critereAdmission): self
    {
        $this->critereAdmission = $critereAdmission;

        return $this;
    }

    /**
     * @return Collection|GroupeCompetences[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
            $groupeCompetence->addReferentiel($this);
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if ($this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences->removeElement($groupeCompetence);
            $groupeCompetence->removeReferentiel($this);
        }

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

    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setReferentiel($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->contains($promo)) {
            $this->promos->removeElement($promo);
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiel() === $this) {
                $promo->setReferentiel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Brief[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
            $brief->setReferentiel($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->contains($brief)) {
            $this->briefs->removeElement($brief);
            // set the owning side to null (unless already changed)
            if ($brief->getReferentiel() === $this) {
                $brief->setReferentiel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StatistiquesCompetences[]
     */
    public function getStatistiquesCompetences(): Collection
    {
        return $this->statistiquesCompetences;
    }

    public function addStatistiquesCompetence(StatistiquesCompetences $statistiquesCompetence): self
    {
        if (!$this->statistiquesCompetences->contains($statistiquesCompetence)) {
            $this->statistiquesCompetences[] = $statistiquesCompetence;
            $statistiquesCompetence->setReferentiel($this);
        }

        return $this;
    }

    public function removeStatistiquesCompetence(StatistiquesCompetences $statistiquesCompetence): self
    {
        if ($this->statistiquesCompetences->contains($statistiquesCompetence)) {
            $this->statistiquesCompetences->removeElement($statistiquesCompetence);
            // set the owning side to null (unless already changed)
            if ($statistiquesCompetence->getReferentiel() === $this) {
                $statistiquesCompetence->setReferentiel(null);
            }
        }

        return $this;
    }
}
