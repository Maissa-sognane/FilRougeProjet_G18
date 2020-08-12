<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Competences;
use App\Repository\CompetencesRepository;
use App\Repository\GroupeCompetencesRepository;
use App\Repository\ReferentielRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\GroupeCompetences;

class GroupeCompetencesController extends AbstractController
{
    /**
     * @Route(name="creategrpcompetences",
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
     * @param ReferentielRepository $repository
     * @param UserRepository $rep
     * @return JsonResponse
     */
    public function addGrpCompetences(ValidatorInterface $validator, SerializerInterface $serializer,
                                        EntityManagerInterface $manager, Request $request, ReferentielRepository $repository,
                                        UserRepository $rep)
    {
        $grpcompetences = $request->getContent();
        $groupecompetences = $serializer->deserialize($grpcompetences, GroupeCompetences::class, "json");
        $groupecompetences->setIsdeleted(false);
        $token = substr($request->server->get("HTTP_AUTHORIZATION"), 7);
        $token = explode(".", $token);
        $playload = $token[1];
        $playload = json_decode(base64_decode($playload));
        $email = $playload->username;
        $user = $rep->findOneBy(["email"=>$email]);
        $groupecompetences->setUser($user);
        $errors = $validator->validate($groupecompetences);
        if($errors === null){
            $manager->persist($groupecompetences);
            $manager->flush();
            return $this->json($groupecompetences,Response::HTTP_CREATED);
        }else{
            return $this->json($groupecompetences,Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route(name="listegrpcompetences",
     *   path="api/admin/grpecompetences",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerGroupeCompetencesController::showGrpCompetences",
     *     "_api_resource_class"=GroupeCompetences::class,
     *     "_api_collection_operation_name"="getgrpecompetences",
     *    }
     * )
     * @param GroupeCompetencesRepository $repository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function showGrpCompetences(GroupeCompetencesRepository $repository, SerializerInterface $serializer){
            $grpecompetences = $repository->findAll();
            $grpecompetences = $serializer->serialize($grpecompetences, "json", []);
            return new JsonResponse($grpecompetences,Response::HTTP_OK,[],true);
    }

    /**
     * @Route(name="listegrpcompetencesById",
     *   path="api/admin/grpecompetences/{id}",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerGroupeCompetencesController::showGrpCompetencesByid",
     *     "_api_resource_class"=GroupeCompetences::class,
     *     "_api_item_operation_name"="getgrpecompetencesbyid",
     *    }
     * )
     * @param GroupeCompetencesRepository $repository
     * @param $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */

    public function showGrpCompetencesByid(GroupeCompetencesRepository $repository, $id, SerializerInterface $serializer){
        $grpecomp = $repository->find($id);
        $grpecompserialise = $serializer->serialize($grpecomp, "json", []);
        return new JsonResponse($grpecompserialise, Response::HTTP_OK,[], true);
    }

    /**
     * @Route(name="updategrpcompetences",
     *   path="api/admin/grpecompetences/{id}",
     *   methods={"PUT"},
     *   defaults={
     *     "_controller"="\app\ControllerGroupeCompetencesController::updategrpeComptence",
     *     "_api_resource_class"=GroupeCompetences::class,
     *     "_api_item_operation_name"="updategrpecompetencesbyid",
     *    }
     * )
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param GroupeCompetencesRepository $repository
     * @param $id
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $manager
     * @param CompetencesRepository $rep
     * @return JsonResponse
     */

    public function updategrpeComptence(Request $request, SerializerInterface $serializer, GroupeCompetencesRepository $repository,
                                        $id, ValidatorInterface $validator, EntityManagerInterface $manager,CompetencesRepository $rep){
        $grpeGroupe = $request->getContent();
        $groupecompetences = $serializer->decode($grpeGroupe, "json");
        $comp = $repository->find($id);
        $comp->setLibelle($groupecompetences['libelle']);
        $comp->setDescription($groupecompetences['description']);
        $comp->setIsdeleted(false);
        foreach ($groupecompetences['competences'] as $competence){
            //Modification Competences
                if(count($competence) === 4){
                    $competences = $rep->find($competence['id']);
                    $competences->setLibelle($competence['libelle']);
                    $competences->setDescriptif($competence['descriptif']);
                    $competences->setIsdeleted(false);
                }
            //Enregistrement nouvelle competence
                if(count($competence) === 3){
                    $competences = $serializer->encode($competence, "json");
                    $competences = $serializer->deserialize($competences, GroupeCompetences::class, "json");
                   // $competences->addGroupeCompetence($comp);
                    /*
                   $competences = new Competences();
                   $competences->setLibelle($competence['libelle']);
                   $competences->setDescriptif($competence['descriptif']);
                   $competences->setIsdeleted(false);
                   $comp->addCompetence($competences);
                    */
                }
            //Suppression Competence
                if(count($competence) === 1){
                    $competences = $rep->find($competence['id']);
                    $competences->setIsdeleted(true);
                }
        }
        $manager->persist($comp);
        $manager->flush();

       // dd($competences);
        $errors = $validator->validate($comp);
        if($errors === null){
          //  $manager->persist($groupeCompetencesObjet);
           //     $manager->persist($competences);
           //    $manager->flush();

            return $this->json($comp,Response::HTTP_CREATED);
        }else{
            return $this->json($comp,Response::HTTP_BAD_REQUEST);
        }

    }
}
