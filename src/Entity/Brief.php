<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BriefRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

//,

/**
 * @ApiResource(
 *
 *     collectionOperations={
 *       "getbrief"={
 *          "method"="GET",
 *          "path"="formateurs/briefs",
 *          "route_name"="listebrief",
 *          "normalization_context"={"groups":"brief:read"},
 *     },
 *     "getbriefbygroupe"={
 *        "method"="GET",
 *        "path"="formateurs/promo/{id}/groupe/{groupe}/briefs",
 *        "route_name"="listebriefbygroupe",
 *        "normalization_context"={"groups":"briefpromogroupe:read"},
 *
 *     },
 *     "getbriefbyformateur"={
 *          "method"="GET",
 *          "path"="formateurs/promos/{id}/briefs",
 *          "route_name"="listebriefbyformateur",
 *          "normalization_context"={"groups":"briefpromogroupe:read"},
 *     },
 *     "getbriefbroullonsbyformateur"={
 *          "path"="formateurs/{id}/briefs/broullons",
 *          "method"="GET",
 *          "route_name"="listebriefbroullonsformateurs",
 *          "normalization_context"={"groups":"brief:read"},
 *     },
 *     "getbriefValidebyformateur"={
 *           "path"="formateurs/{id}/briefs/valide",
 *           "method"="GET",
 *          "route_name"="listebriefvalideformateurs",
 *          "normalization_context"={"groups":"brief:read"},
 *     },
 *     "getbriefApprenant"={
 *          "path"="apprenants/promos/{id}/briefs",
 *          "method"="GET",
 *          "route_name"="listebriefApprenant",
 *           "normalization_context"={"groups":"briefpromogroupe:read"},
 *
 *     },
 *  "getbriefApprenantById"={
 *          "path"="apprenants/promo/{id}/briefs/{brief}",
 *          "method"="GET",
 *          "route_name"="listebriefApprenantById",
 *          "normalization_context"={"groups":"briefpromogroupe:read"},
 *
 *     },
 *     "getpromobrief"={
 *          "path"="formateurs/promo/{id}/briefs/{brief}",
 *          "method"="GET",
 *          "route_name"="listepromobrief",
 *          "normalization_context"={"groups":"briefpromogroupe:read"},
 *     }
 *     },
 *     itemOperations={
 *       "get"={
 *          "path"="formateur/briefs/{id}",
 *          "method"="GET"
 *     }
 *     }
 * )
 * @ORM\Entity(repositoryClass=BriefRepository::class)
 */
class Brief
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"brief:read", "briefpromogroupe:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read"})
     */
    private $contexte;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read"})
     */
    private $livrableAttendus;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read"})
     */
    private $modalitePedagogique;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read"})
     */
    private $criterePerformance;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read"})
     */
    private $modaliteEvaluation;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statutBrief;

    /**
     * @ORM\OneToMany(targetEntity=PromoBrief::class, mappedBy="brief")
     */
    private $promobrief;

    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="brief")
     * @Groups({"brief:read", "briefpromogroupe:read"})
     */
    private $ressource;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="briefs")
     * @Groups({"briefpromogroupe:read"})
     */
    private $referentiel;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="briefs")
     * @Groups({"brief:read", "briefpromogroupe:read"})
     */
    private $tag;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     * @Groups({"briefpromogroupe:read"})
     */
    private $formateur;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, inversedBy="briefs")
     * @Groups({"briefpromogroupe:read"})
     */
    private $groupe;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="brief")
     * @Groups({"brief:read", "briefpromogroupe:read"})
     */
    private $niveau;

    /**
     * @ORM\ManyToMany(targetEntity=Livrableattendus::class, mappedBy="brief")
     * @Groups({"brief:read", "briefpromogroupe:read"})
     */
    private $livrableattenduses;

    /**
     * @ORM\OneToMany(targetEntity=BriefGroupe::class, mappedBy="brief")
     */
    private $briefGroupes;

    public function __construct()
    {
        $this->promobrief = new ArrayCollection();
        $this->ressource = new ArrayCollection();
        $this->tag = new ArrayCollection();
        $this->groupe = new ArrayCollection();
        $this->niveau = new ArrayCollection();
        $this->livrableattenduses = new ArrayCollection();
        $this->briefGroupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContexte(): ?string
    {
        return $this->contexte;
    }

    public function setContexte(string $contexte): self
    {
        $this->contexte = $contexte;

        return $this;
    }

    public function getLivrableAttendus(): ?string
    {
        return $this->livrableAttendus;
    }

    public function setLivrableAttendus(string $livrableAttendus): self
    {
        $this->livrableAttendus = $livrableAttendus;

        return $this;
    }

    public function getModalitePedagogique(): ?string
    {
        return $this->modalitePedagogique;
    }

    public function setModalitePedagogique(string $modalitePedagogique): self
    {
        $this->modalitePedagogique = $modalitePedagogique;

        return $this;
    }

    public function getCriterePerformance(): ?string
    {
        return $this->criterePerformance;
    }

    public function setCriterePerformance(string $criterePerformance): self
    {
        $this->criterePerformance = $criterePerformance;

        return $this;
    }

    public function getModaliteEvaluation(): ?string
    {
        return $this->modaliteEvaluation;
    }

    public function setModaliteEvaluation(string $modaliteEvaluation): self
    {
        $this->modaliteEvaluation = $modaliteEvaluation;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getStatutBrief(): ?string
    {
        return $this->statutBrief;
    }

    public function setStatutBrief(string $statutBrief): self
    {
        $this->statutBrief = $statutBrief;

        return $this;
    }

    /**
     * @return Collection|PromoBrief[]
     */
    public function getPromobrief(): Collection
    {
        return $this->promobrief;
    }

    public function addPromobrief(PromoBrief $promobrief): self
    {
        if (!$this->promobrief->contains($promobrief)) {
            $this->promobrief[] = $promobrief;
            $promobrief->setBrief($this);
        }

        return $this;
    }

    public function removePromobrief(PromoBrief $promobrief): self
    {
        if ($this->promobrief->contains($promobrief)) {
            $this->promobrief->removeElement($promobrief);
            // set the owning side to null (unless already changed)
            if ($promobrief->getBrief() === $this) {
                $promobrief->setBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ressource[]
     */
    public function getRessource(): Collection
    {
        return $this->ressource;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressource->contains($ressource)) {
            $this->ressource[] = $ressource;
            $ressource->setBrief($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressource->contains($ressource)) {
            $this->ressource->removeElement($ressource);
            // set the owning side to null (unless already changed)
            if ($ressource->getBrief() === $this) {
                $ressource->setBrief(null);
            }
        }

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

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->contains($tag)) {
            $this->tag->removeElement($tag);
        }

        return $this;
    }

    public function getFormateur(): ?Formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Formateur $formateur): self
    {
        $this->formateur = $formateur;

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupe(): Collection
    {
        return $this->groupe;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupe->contains($groupe)) {
            $this->groupe[] = $groupe;
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupe->contains($groupe)) {
            $this->groupe->removeElement($groupe);
        }

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveau(): Collection
    {
        return $this->niveau;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveau->contains($niveau)) {
            $this->niveau[] = $niveau;
            $niveau->setBrief($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveau->contains($niveau)) {
            $this->niveau->removeElement($niveau);
            // set the owning side to null (unless already changed)
            if ($niveau->getBrief() === $this) {
                $niveau->setBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Livrableattendus[]
     */
    public function getLivrableattenduses(): Collection
    {
        return $this->livrableattenduses;
    }

    public function addLivrableattendus(Livrableattendus $livrableattendus): self
    {
        if (!$this->livrableattenduses->contains($livrableattendus)) {
            $this->livrableattenduses[] = $livrableattendus;
            $livrableattendus->addBrief($this);
        }

        return $this;
    }

    public function removeLivrableattendus(Livrableattendus $livrableattendus): self
    {
        if ($this->livrableattenduses->contains($livrableattendus)) {
            $this->livrableattenduses->removeElement($livrableattendus);
            $livrableattendus->removeBrief($this);
        }

        return $this;
    }

    /**
     * @return Collection|BriefGroupe[]
     */
    public function getBriefGroupes(): Collection
    {
        return $this->briefGroupes;
    }

    public function addBriefGroupe(BriefGroupe $briefGroupe): self
    {
        if (!$this->briefGroupes->contains($briefGroupe)) {
            $this->briefGroupes[] = $briefGroupe;
            $briefGroupe->setBrief($this);
        }

        return $this;
    }

    public function removeBriefGroupe(BriefGroupe $briefGroupe): self
    {
        if ($this->briefGroupes->contains($briefGroupe)) {
            $this->briefGroupes->removeElement($briefGroupe);
            // set the owning side to null (unless already changed)
            if ($briefGroupe->getBrief() === $this) {
                $briefGroupe->setBrief(null);
            }
        }

        return $this;
    }
}