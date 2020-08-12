<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\GroupeCompetences;
use App\Repository\GroupeCompetencesRepository;
use App\Repository\ReferentielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Referentiel;
use Symfony\Component\Security\Core\User\UserInterface;

class ReferentielController extends AbstractController
{
    /**
     *  @Route(name="createreferentiels",
     *   path="api/admin/referentiels",
     *   methods={"POST"},
     *   defaults={
     *     "_controller"="\app\ControllerReferentielController::createReferentiel",
     *     "_api_resource_class"=Referentiel::class,
     *     "_api_collection_operation_name"="postreferentiels",
     *    }
     * )
     * @param ReferentielRepository $repository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $manager
     * @param UserInterface $user
     * @param GroupeCompetencesRepository $rep
     * @return JsonResponse
     */

    public function createReferentiel(ReferentielRepository $repository, SerializerInterface $serializer,
                                        Request $request, ValidatorInterface $validator, EntityManagerInterface $manager,
                                         UserInterface $user, GroupeCompetencesRepository $rep)
    {
        $referentiels = $request->getContent();
        // $programme = $request->files->get("programme");
        // $programme = fopen($programme->getRealPath(), "br");
        //  $programme['programme'] = $programme;
        $referentiels = $serializer->decode($referentiels, "json");
        $ref = $rep->find($referentiels['groupeCompetences'][0]['id']);

        $ref = new Referentiel();
        $ref->setLibelle($referentiels['libelle']);
        $ref->setCritereAdmission($referentiels['critereAdmission']);
        $ref->setCritereEvaluation($referentiels['critereEvaluation']);
        $ref->setPresentation($referentiels['presentation']);
        $ref->setIsdeleted(false);
        foreach ($referentiels['groupeCompetences'] as $grpe){
            $grpecompetence = $rep->find($grpe['id']);
            if($grpecompetence === null){
                return $this->json($ref, Response::HTTP_BAD_REQUEST);
            }else{
                $ref->addGroupeCompetence($grpecompetence);
            }
        }
          $manager->persist($ref);
          $manager->flush();
          return $this->json($ref, Response::HTTP_CREATED);
    }

    /**
     *  * @Route(name="createreferentielsbyid",
     *   path="api/admin/referentiels/{id}",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerReferentielController::showReferentielByid",
     *     "_api_resource_class"=Referentiel::class,
     *     "_api_item_operation_name"="postreferentielsbyid",
     *    }
     * )
     * @param ReferentielRepository $repository
     * @param $id
     * @param SerializerInterface $serializer
     * @return Referentiel
     */

    public function showReferentielByid(ReferentielRepository $repository, $id, SerializerInterface $serializer){
        $ref = $repository->find($id);
      //  $ref = $serializer->serialize($ref, "json");
        return $ref;
    }

    /**
     *  @Route(name="grpecompetenceCompetences",
     *   path="api/admin/referentiels_grpecompetences",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerReferentielController::showGrpeCompetences",
     *     "_api_resource_class"=GroupeCompetences::class,
     *     "_api_collection_operation_name"="GetgrpecompetenceCompetences",
     *    }
     * )
     * @param GroupeCompetencesRepository $repository
     * @return GroupeCompetences[]
     */

    public function showGrpeCompetences(GroupeCompetencesRepository $repository){
            $grpe = $repository->findAll();
            return $grpe;
    }

    /**
     * @Route(name="updateref",
     *   path="api/admin/referentiels/{id}",
     *   methods={"PUT"},
     *   defaults={
     *     "_controller"="\app\ControllerReferentielController::updateReferentiel",
     *     "_api_resource_class"=Referentiel::class,
     *     "_api_item_operation_name"="putreferentiel",
     *    }
     *
     *  )
     * @param ReferentielRepository $repository
     * @param $id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param GroupeCompetencesRepository $rep
     * @return JsonResponse
     */
    public function updateReferentiel(ReferentielRepository $repository, $id, Request $request, SerializerInterface $serializer,
                                        EntityManagerInterface $manager, GroupeCompetencesRepository $rep){
            $referentiel = $request->getContent();
            $referentielJson = $serializer->decode($referentiel, "json");
            $ref = $repository->find($id);
            $ref->setLibelle($referentielJson['libelle']);
            $ref->setPresentation($referentielJson['presentation']);
            // $programme = $request->files->get("programme");
            // $programme = fopen($programme->getRealPath(), "br");
            //  $programme['programme'] = $programme;
            $ref->setCritereEvaluation($referentielJson['critereEvaluation']);
            $ref->setCritereAdmission($referentielJson['critereAdmission']);
            foreach ($referentielJson['groupeCompetences'] as $grpe){
                if(count($grpe) === 1){
                    $grpecompetence = $rep->find($grpe['id']);
                    $grpecompetence->addReferentiel($ref);
                }
                if(count($grpe) === 4){
                    $grpecompetence = $rep->find($grpe['id']);
                    $grpecompetence->removeReferentiel($ref);
                }
        }
            $manager->persist($ref);
            $manager->flush();
            return $this->json($ref, Response::HTTP_OK);

    }
}
