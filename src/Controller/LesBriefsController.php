<?php

namespace App\Controller;

use App\Repository\BriefRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Brief;

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
     * @return array
     */
    public function showBriefByGroupe(BriefRepository $repo_brief, $id,  PromoRepository $repo_promo,
                                        GroupeRepository $repo_groupe, $groupe){

          //  $groupes = $repo_groupe->find($groupe);
            $briefs = $repo_brief->findAll();
            $brief = [];
            foreach ($briefs as $br){
                $prom = $br->getPromobrief()->getValues();
                foreach ($prom as $promo){

                    if($id == $promo->getPromo()->getId()){
                       // dd($promo->getPromo()->getGroupe());
                       // $promos = $repo_promo->find($promo->getId());
                        $groupes = $promo->getPromo()->getGroupe();
                        foreach ($groupes as $grpe){
                            if($grpe->getId() == $groupe){
                                $brief[] =  $promo;
                            }
                        }
                    }
                }
               // dd($br->getPromobrief()->getValues());
            }
           // dd($briefs[0]->getGroupe()->getValues()[0]->getPromo());
        return $brief;
    }

}
