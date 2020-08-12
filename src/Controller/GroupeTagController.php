<?php

namespace App\Controller;

use App\Entity\GroupeCompetences;
use App\Entity\Tag;
use App\Repository\GroupeCompetencesRepository;
use App\Repository\GroupeTagRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\GroupeTag;

class GroupeTagController extends AbstractController
{
    /**
     * @Route(name="creategrptag",
     *   path="api/admin/grpetags",
     *   methods={"POST"},
     *   defaults={
     *     "_controller"="\app\ControllerGroupeTagController::addGrpTag",
     *     "_api_resource_class"=GroupeTag::class,
     *     "_api_collection_operation_name"="postgrpetag",
     *    }
     * )
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function addGrpTag(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $grpetag = $request->getContent();
        $grpetag = $serializer->deserialize($grpetag, GroupeTag::class, "json");
        $manager->persist($grpetag);
        $manager->flush();
        return $this->json($grpetag, Response::HTTP_CREATED);
    }

    /**
     * @Route(name="updategrptag",
     *   path="api/admin/grpetags/{id}",
     *   methods={"PUT"},
     *   defaults={
     *     "_controller"="\app\ControllerGroupeTagController::updateGrpTag",
     *     "_api_resource_class"=GroupeTag::class,
     *     "_api_item_operation_name"="putgrpetag",
     *    }
     * )
     * @param GroupeTagRepository $repository
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param $id
     * @param TagRepository $repo
     */

    public function updateGrpTag(GroupeTagRepository $repository, Request $request, SerializerInterface $serializer,
                                    EntityManagerInterface $manager, $id, TagRepository $repo){
        $grpetag = $request->getContent();
        $grpetag = $serializer->decode($grpetag, "json");
        $tag = $repository->find($id);
        $tag->setLibelle($grpetag['libelle']);
        $tag->setIsdeleted(false);
        foreach ($grpetag['tag'] as $grpe){
            if(count($grpe) === 4){
                    $groupetag = $repo->find($grpe['id']);
                    $groupetag->setLibelle($grpe['libelle']);
                    $groupetag->setDescriptif($grpe['descriptif']);
                    $groupetag->setIsdeleted(false);
            }
            if(count($grpe) === 3){
                    $groupetag = $serializer->encode($grpe, "json");
                    $groupetag = $serializer->deserialize($groupetag, GroupeTag::class, "json");
                  //  $groupetag->addGroupeTag($tag);
                    $groupetag = null;
            }
        }
        $manager->persist($tag);
        $manager->flush();
    }
}
