<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      collectionOperations={
 *         "get"={
 *              "security"="is_granted('VIEW', object)",
 *              "security_message"="vous n'avez pas acces",
 *              "path"="admin/tags",
 *              "normalization_context"={"groups":"tag_read"},
 *     },
 *     "post"={
 *        "security"="is_granted('VIEW', object)",
 *        "security_message"="vous n'avez pas acces",
 *        "path"="admin/tags",
 *     }
 *     },
 *     itemOperations={
 *         "get"={
 *              "security"="is_granted('VIEW', object)",
 *              "security_message"="vous n'avez pas acces",
 *              "path"="admin/tags/{id}",
 *
 *     },
 *     "put"={
 *        "security"="is_granted('VIEW', object)",
 *        "security_message"="vous n'avez pas acces",
 *        "path"="admin/tags/{id}",
 *
 *     }
 *     }
 * )
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpetag_write", "grpetag_read", "tag_read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpetag_write", "grpetag_read", "tag_read"})
     */
    private $descriptif;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"grpetag_write", "grpetag_read", "tag_read"})
     */
    private $isdeleted;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTag::class, mappedBy="tag")
     * @Groups({"tag_read"})
     */
    private $groupeTags;

    public function __construct()
    {
        $this->groupeTags = new ArrayCollection();
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

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

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
     * @return Collection|GroupeTag[]
     */
    public function getGroupeTags(): Collection
    {
        return $this->groupeTags;
    }

    public function addGroupeTag(GroupeTag $groupeTag): self
    {
        if (!$this->groupeTags->contains($groupeTag)) {
            $this->groupeTags[] = $groupeTag;
            $groupeTag->addTag($this);
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTag $groupeTag): self
    {
        if ($this->groupeTags->contains($groupeTag)) {
            $this->groupeTags->removeElement($groupeTag);
            $groupeTag->removeTag($this);
        }

        return $this;
    }
}
