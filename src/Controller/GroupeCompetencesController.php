<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\GroupeCompetences;

class GroupeCompetencesController extends AbstractController
{
    /**
     *  * @Route(name="creategrpcompetences",
     *   path="api/admin/grpecompetences",
     *   methods={"POST"},
     *   defaults={
     *     "_controller"="\app\ControllerGroupeCompetencesController::addGrpCompetences",
     *     "_api_resource_class"=GroupeCompetences::class,
     *     "_api_collection_operation_name"="postgrpecompetences",
     *    }
     * )
     *
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return void
     */
    public function addGrpCompetences(ValidatorInterface $validator, SerializerInterface $serializer,
                                        EntityManagerInterface $manager, Request $request)
    {
        $grpcompetences = $request->getContent();
        dd($grpcompetences);
    }
}
