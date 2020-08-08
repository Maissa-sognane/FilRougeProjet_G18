<?php

namespace App\Controller;
use ApiPlatform\Core\Bridge\Symfony\Validator\Validator;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\GroupeCompetences;
use App\Entity\Niveau;
use App\Repository\CompetencesRepository;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Competences;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

class CompetencesController extends AbstractController
{
    /**
     * @Route(name="createcompetences",
     *   path="api/admin/competences",
     *   methods={"POST"},
     *   defaults={
     *     "_controller"="\app\ControllerCompetencesController::addCompetences",
     *     "_api_resource_class"=Competences::class,
     *     "_api_collection_operation_name"="postcompetences",
     *    }
     * )
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function addCompetences(Request $request, SerializerInterface $serializer, ValidatorInterface $validator,
                                    EntityManagerInterface $manager)
    {
        $competences = $request->getContent();
        $competencesSerial = $serializer->deserialize($competences, Competences::class, "json");
        $competencesSerial->setIsdeleted(false);

        $error = $validator->validate($competencesSerial);
        if($error === null){
            $manager->persist($competencesSerial);
            $manager->flush();
            return $this->json($competencesSerial,Response::HTTP_CREATED);
        }
        else{
            return $this->json($competencesSerial,Response::HTTP_BAD_REQUEST);
        }
    }
    /**
     * @Route(name="listecompetencesById",
     *   path="api/admin/competences/{id}",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\ControllerCompetencesController::showCompetencesByid",
     *     "_api_resource_class"=Competences::class,
     *     "_api_item_operation_name"="getcompetencesbyid",
     *    }
     * )
     * @param CompetencesRepository $repository
     * @param $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */

    public function showCompetencesByid(CompetencesRepository $repository, $id, SerializerInterface $serializer){
        $competences = $repository->find($id);
        $competences = $serializer->serialize($competences, "json", []);
        return new JsonResponse($competences, Response::HTTP_OK,[], true);
    }

    /**
     * @Route(name="updatecompetences",
     *   path="api/admin/competences/{id}",
     *   methods={"PUT"},
     *   defaults={
     *     "_controller"="\app\ControllerCompetencesController::updateCompetences",
     *     "_api_resource_class"=Competences::class,
     *     "_api_item_operation_name"="updatecompetencesid",
     *    }
     * )
     * @param CompetencesRepository $repository
     * @param $id
     * @param EntityManagerInterface $manager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param NiveauRepository $rep
     * @param Request $request
     * @return JsonResponse
     */

    public function updateCompetences(CompetencesRepository $repository, $id, EntityManagerInterface $manager,
                                        SerializerInterface $serializer, ValidatorInterface $validator,
                                        NiveauRepository $rep, Request $request){

        $competences = $request->getContent();
        $competencesSerialise = $serializer->decode($competences, "json");
        $competenceObjet = $repository->find($id);
        $competenceObjet->setLibelle($competencesSerialise['libelle']);
        $competenceObjet->setDescriptif($competencesSerialise['descriptif']);
        $competenceObjet->setIsdeleted($competencesSerialise['isdeleted']);
        foreach ($competencesSerialise['niveau'] as $niveauxTab){
            $i=0;
           // Modification Niveaux
            if(count($niveauxTab) === 5){
                $niveaux = $rep->find($niveauxTab['id']);
                $niveaux->setLibelle($niveauxTab['libelle']);
                $niveaux->setCritereEvaluation($niveauxTab['critereEvaluation']);
                $niveaux->setGroupeAction($niveauxTab['groupeAction']);
                $niveaux->setIsdeleted(false);
                $niveaux->setCompetences($competenceObjet);
            }
            //Creation Niveau
            if(count($niveauxTab) === 4){
                $niveaux = $serializer->encode($niveauxTab, "json");
                $niveaux = $serializer->deserialize($niveaux, Niveau::class, "json");
                /*
                $niveaux = new Niveau();
                $niveaux->setLibelle($niveauxTab['libelle']);
                $niveaux->setCritereEvaluation($niveauxTab['critereEvaluation']);
                $niveaux->setGroupeAction($niveauxTab['groupeAction']);
                $niveaux->setIsdeleted(false);
                $niveaux->setCompetences($competenceObjet);
                $competenceObjet->addNiveau($niveaux);
                */
            }
            //Suppresion Niveaux
            if(count($niveauxTab) === 1){
                $niveaux = $rep->find($niveauxTab['id']);
                $niveaux->setIsdeleted(true);
                $niveaux->setCompetences($competenceObjet);
            }
        }
        $error = $validator->validate($competenceObjet);
        if($error === null){
            $manager->persist($competenceObjet);
            $manager->flush();
            return $this->json($competenceObjet, Response::HTTP_CREATED );
        }

    }

}
