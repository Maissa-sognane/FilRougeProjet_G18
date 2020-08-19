<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CMRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

/**
 * @ApiResource(
 *      *     itemOperations={
 *       "get"={
 *          "path"="cm/{id}",
 *          "defaults"={"id"=null}
 *     }
 *     }
 * )
 * @ORM\Entity(repositoryClass=CMRepository::class)
 */
class CM extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
