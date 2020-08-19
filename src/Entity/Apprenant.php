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
 *     },
 *     "postApprenant"={
 *          "method"="POST",
 *          "path"="/apprenants",
 *          "route_name"="createApprenant"
 *     }
 *     },
 *    itemOperations={
 *       "get"={
 *          "path"="apprenants/{id}",
 *          "defaults"={"id"=null},
 *          "normalization_context"={"groups":"apprenant:write"},
 *     },
 * "postApprenant"={
 *       "path"="/apprenants/{id}",
 *       "method"="PUT",
 *       "route_name"="createApprenant",
 *     },
 * "getApprenantId"={
 *       "path"="/apprenants/{id}",
 *       "method"="GET",
 *       "route_name"="listerApprenantById",
 *       "normalization_context"={"groups":"apprenant:write"},
 *     }
 *     }
 * )
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
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

    public function __construct()
    {
        parent::__construct();
        $this->groupes = new ArrayCollection();
        $this->livrables = new ArrayCollection();
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
}
