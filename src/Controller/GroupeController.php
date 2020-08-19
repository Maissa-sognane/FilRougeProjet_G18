<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Groupe;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromoRepository;
use App\Repository\ReferentielRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GroupeController extends AbstractController
{
    /**
     * @Route(name="listegroupeapprenant",
     *   path="api/admin/groupes/apprenants",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerGroupeController::showGroupeApprenant",
     *     "_api_resource_class"=Groupe::class,
     *     "_api_collection_operation_name"="getlistegroupeapprenant",
     *    }
     * )
     * @param GroupeRepository $repo_groupe
     * @return Groupe[]
     */

    public function showGroupeApprenant(GroupeRepository $repo_groupe){
        $groupe = $repo_groupe->findAll();
        return $groupe;
    }

    /**
     * @Route(name="createapprenantformateur",
     *   path="api/admin/groupes",
     *   methods={"POST"},
     *   defaults={
     *     "_controller"="\app\ControllerGroupeController::createFormateurApprenant",
     *     "_api_resource_class"=Groupe::class,
     *     "_api_collection_operation_name"="postapprenantformateur",
     *    }
     * )
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param PromoRepository $repo_promo
     * @param ApprenantRepository $repo_apprenant
     * @param FormateurRepository $repo_forma
     * @return JsonResponse
     * @throws Exception
     */

    public function createFormateurApprenant(Request $request, SerializerInterface $serializer,
                                             EntityManagerInterface $manager, PromoRepository $repo_promo,
                                            ApprenantRepository $repo_apprenant, FormateurRepository $repo_forma){
        $groupe = $request->getContent();
        $groupeTab = $serializer->decode($groupe, "json");
        $groupeObj = $serializer->deserialize($groupe, Groupe::class, "json");
        $date = new \DateTime('@'.strtotime('now'));
        $groupeObj->setDateCreation($date);
        $promo = $repo_promo->find($groupeTab['promo']);
        $groupeObj->setPromo($promo);
        foreach ($groupeTab['formateur'] as $formateur){
            $formateurs = $repo_forma->find($formateur['id']);
            $groupeObj->addFormateur($formateurs);
        }
        foreach ($groupeTab['apprenant'] as $apprenant){
            $apprenants = $repo_apprenant->findByEmail($apprenant['email']);
            $groupeObj->addApprenant($apprenants[0]);
        }
        $manager->persist($groupeObj);
        $manager->flush();
        return $this->json($groupeObj, Response::HTTP_OK);
    }

    /**
     * @Route(name="updateapprenantgroupe",
     *   path="api/admin/groupes/{id}/apprenants",
     *   methods={"PUT"},
     *   defaults={
     *     "_controller"="\app\ControllerGroupeController::updateGroupeApprenant",
     *     "_api_resource_class"=Groupe::class,
     *     "_api_item_operation_name"="putapprenantgroupe",
     *    }
     * )
     * @param Request $request
     * @param $id
     * @param ApprenantRepository $repo_apprenant
     * @param GroupeRepository $repo_groupe
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */

    public function updateGroupeApprenant(Request $request, $id,ApprenantRepository $repo_apprenant, GroupeRepository $repo_groupe,
                                            SerializerInterface $serializer, EntityManagerInterface $manager){
            $groupe = $repo_groupe->find($id);
            $groupeJson = $request->getContent();
            $groupeTab = $serializer->decode($groupeJson,"json");
            foreach ($groupeTab['apprenant'] as $app){
                if(isset($app['email'])){
                    $apprenant = $repo_apprenant->findByEmail($app['email']);
                    $groupe->addApprenant($apprenant[0]);
                }
            }
            $manager->persist($groupe);
            $manager->flush();
            return $this->json($groupe, Response::HTTP_OK);
    }

    /**
     * @Route(name="deletegroupeapprenant",
     *   path="api/admin/groupes/{id}/apprenants",
     *   methods={"DELETE"},
     *   defaults={
     *     "_controller"="\app\ControllerGroupeController::deleteApprenant",
     *     "_api_resource_class"=Groupe::class,
     *     "_api_item_operation_name"="deletegroupeapprenant",
     *    }
     * )
     * @param ApprenantRepository $repository
     * @param Request $request
     * @param GroupeRepository $repo_groupe
     * @param SerializerInterface $serializer
     * @param $id
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */

    public function deleteApprenant(ApprenantRepository $repository, Request $request, GroupeRepository $repo_groupe, SerializerInterface $serializer, $id,
                                        EntityManagerInterface $manager){
        $groupe = $repo_groupe->find($id);
        $apprenant = $request->getContent();
        $apprenant = $serializer->decode($apprenant, "json");
        foreach ($apprenant['apprenant'] as $app){
            $apprenantsfind = $repository->find($app['id']);
            $groupe->removeApprenant($apprenantsfind);
        }
        $manager->persist($groupe);
        $manager->flush();
        return $this->json($groupe, Response::HTTP_OK);
    }

}
