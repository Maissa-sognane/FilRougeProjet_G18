<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Entity\Groupe;
use App\Repository\BriefGroupeRepository;
use App\Repository\BriefRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromoBriefRepository;
use App\Repository\PromoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Brief;
use Symfony\Component\Serializer\SerializerInterface;

class LesBriefsController extends AbstractController
{

    /**
     * @Route(name="listebrief",
     *   path="api/formateurs/briefs",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\LesBriefsController::showBrief",
     *     "_api_resource_class"=Brief::class,
     *     "_api_collection_operation_name"="getbrief",
     *    }
     * )
     * @param BriefRepository $repo_brief
     * @return Brief[]
     */

    public function showBrief(BriefRepository $repo_brief){
        $brief = $repo_brief->findAll();
        return $brief;
    }

    /**
     * @Route(name="listebriefbygroupe",
     *   path="api/formateurs/promo/{id}/groupe/{groupe}/briefs",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\LesBriefsController::showBriefByGroupe",
     *     "_api_resource_class"=Brief::class,
     *     "_api_collection_operation_name"="getbriefbygroupe",
     *    }
     * )
     * @param BriefRepository $repo_brief
     * @param $id
     * @param PromoRepository $repo_promo
     * @param GroupeRepository $repo_groupe
     * @param $groupe
     * @param FormateurRepository $repository
     * @param Request $request
     * @param PromoBriefRepository $repo_promobrief
     * @param SerializerInterface $serializer
     * @param BriefGroupeRepository $repo_briefgroupe
     * @return array
     */
    public function showBriefByGroupe(BriefRepository $repo_brief, $id,  PromoRepository $repo_promo,
                                        GroupeRepository $repo_groupe, $groupe, FormateurRepository $repository, Request $request,
                                        PromoBriefRepository $repo_promobrief, SerializerInterface $serializer,
                                        BriefGroupeRepository $repo_briefgroupe){


            $brief = [];
            $token = substr($request->server->get("HTTP_AUTHORIZATION"), 7);
            $token = explode(".", $token);
            $playload = $token[1];
            $playload = json_decode(base64_decode($playload));
            $email = $playload->username;
            $user = $repository->findOneBy(["email"=>$email]);
            $promoBrief = $repo_promobrief->findBy(['promo'=>$id]);
            $groupes = $repo_briefgroupe->findBy(['groupe'=>$groupe]);
            dd($groupes[0]);

            /*
             foreach ($promoBrief as $briefs){
                $groupes = $briefs->getBrief()->getGroupe()->getValues();
                $id_brief = $briefs->getBrief()->getId();
                $grpe_ferm = $briefs->getBrief()->getGroupe()->getValues();
                foreach ($grpe_ferm as $groupeCloturer){
                    $briefs->getBrief()->removeGroupe($groupeCloturer);
                }
                foreach ($groupes as $grpe){
                    if($grpe->getStatut() === true){
                         $briefs->getBrief()->addGroupe($grpe);
                    }
                }
               // $groupe_encour = $serializer->denormalize($groupe_encour, Groupe::class);
               // $briefs->getBrief()->addGroupe($groupe_encour);
                if($briefs->getBrief()->getFormateur()->getEmail() === $email){
                    $brief[] = $briefs;
                }
            }*/
        return $brief;

    }

}
