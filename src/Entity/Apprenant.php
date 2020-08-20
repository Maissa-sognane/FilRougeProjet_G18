<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  collectionOperations={
 *      "getApprenant"={
 *          "method"="GET",
 *          "path"="/apprenants",
 *          "route_name"="ListerApprenant",
 *          "normalization_context"={"groups":"apprenant:write"},
 *          "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *          "security_message"="Vous n'avez pas l'acces"
 *     },
 *     "postApprenant"={
 *          "method"="POST",
 *          "path"="/apprenants",
 *          "route_name"="createApprenant",
 *          "deserialize"=false,
 *           "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas l'acces"
 *     }
 *     },
 *    itemOperations={
 *       "get"={
 *          "path"="apprenants/{id}",
 *          "defaults"={"id"=null},
 *          "normalization_context"={"groups":"apprenant:write"},
 *           "security"="is_granted('ROLE_APPRENANT') or is_granted('ROLE_CM')",
 *          "security_message"="Vous n'avez pas l'acces"
 *     },
 * "postApprenant"={
 *       "path"="/apprenants/{id}",
 *       "method"="PUT",
 *       "route_name"="createApprenant",
 *       "security"="is_granted('ROLE_APPRENANT')",
 *       "security_message"="Vous n'avez pas l'acces"
 *     },
 *     }
 * )
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 */

/*
 *
 *
 * * "getApprenantId"={
 *       "path"="/apprenants/{id}",
 *       "method"="GET",
 *       "route_name"="listerApprenantById",
 *
 *       "normalization_context"={"groups":"apprenant:write"},
 *     }
 */
class Apprenant extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"apprenant:write"})
     *
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenant")
     */
    private $groupes;

    /**
     * @ORM\OneToMany(targetEntity=Livrable::class, mappedBy="apprenant")
     */
    private $livrables;



    /**
     * @ORM\OneToMany(targetEntity=PromoBriefApprenant::class, mappedBy="apprenant")
     */
    private $promoBriefApprenants;

    /**
     * @ORM\OneToMany(targetEntity=StatistiquesCompetences::class, mappedBy="apprenant")
     */
    private $statistiquesCompetences;

    /**
     * @ORM\ManyToOne(targetEntity=ProfilDeSorti::class, inversedBy="apprenant")
     */
    private $profilDeSorti;

    public function __construct()
    {
        parent::__construct();
        $this->groupes = new ArrayCollection();
        $this->livrables = new ArrayCollection();
        $this->profilDeSortis = new ArrayCollection();
        $this->promoBriefApprenants = new ArrayCollection();
        $this->statistiquesCompetences = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->addApprenant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
            $groupe->removeApprenant($this);
        }

        return $this;
    }

    /**
     * @return Collection|Livrable[]
     */
    public function getLivrables(): Collection
    {
        return $this->livrables;
    }

    public function addLivrable(Livrable $livrable): self
    {
        if (!$this->livrables->contains($livrable)) {
            $this->livrables[] = $livrable;
            $livrable->setApprenant($this);
        }

        return $this;
    }

    public function removeLivrable(Livrable $livrable): self
    {
        if ($this->livrables->contains($livrable)) {
            $this->livrables->removeElement($livrable);
            // set the owning side to null (unless already changed)
            if ($livrable->getApprenant() === $this) {
                $livrable->setApprenant(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection|PromoBriefApprenant[]
     */
    public function getPromoBriefApprenants(): Collection
    {
        return $this->promoBriefApprenants;
    }

    public function addPromoBriefApprenant(PromoBriefApprenant $promoBriefApprenant): self
    {
        if (!$this->promoBriefApprenants->contains($promoBriefApprenant)) {
            $this->promoBriefApprenants[] = $promoBriefApprenant;
            $promoBriefApprenant->setApprenant($this);
        }

        return $this;
    }

    public function removePromoBriefApprenant(PromoBriefApprenant $promoBriefApprenant): self
    {
        if ($this->promoBriefApprenants->contains($promoBriefApprenant)) {
            $this->promoBriefApprenants->removeElement($promoBriefApprenant);
            // set the owning side to null (unless already changed)
            if ($promoBriefApprenant->getApprenant() === $this) {
                $promoBriefApprenant->setApprenant(null);
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
            $statistiquesCompetence->setApprenant($this);
        }

        return $this;
    }

    public function removeStatistiquesCompetence(StatistiquesCompetences $statistiquesCompetence): self
    {
        if ($this->statistiquesCompetences->contains($statistiquesCompetence)) {
            $this->statistiquesCompetences->removeElement($statistiquesCompetence);
            // set the owning side to null (unless already changed)
            if ($statistiquesCompetence->getApprenant() === $this) {
                $statistiquesCompetence->setApprenant(null);
            }
        }

        return $this;
    }

    public function getProfilDeSorti(): ?ProfilDeSorti
    {
        return $this->profilDeSorti;
    }

    public function setProfilDeSorti(?ProfilDeSorti $profilDeSorti): self
    {
        $this->profilDeSorti = $profilDeSorti;

        return $this;
    }
}
