<?php

namespace App\Controller;

use App\Entity\BriefGroupe;
use App\Entity\Formateur;
use App\Entity\Groupe;
use App\Entity\PromoBrief;
use App\Repository\BriefGroupeRepository;
use App\Repository\BriefRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromoBriefRepository;
use App\Repository\PromoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Brief;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Serializer\SerializerInterface;

class BriefController extends AbstractController
{
    /**
     * @Route(name="listebrief",
     *   path="api/formateurs/briefs",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\BriefController::showBrief",
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
     *     "_controller"="\app\BriefController::showBriefByGroupe",
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
        if(is_string($groupe)){
            $groupes = $repo_briefgroupe->findBy(['groupe'=>$groupe]);
        }
        if($groupe === "briefGroupe"){
            $groupes = $repo_briefgroupe->findAll();
        }
        if(isset($groupes)){
            foreach ($groupes as $grpe){
                $groupebriefs = $grpe->getBrief()->getGroupe()->getValues();
                $brief_id = $grpe->getBrief()->getId();
                foreach ($groupebriefs as $groupebrief){
                    if($groupebrief->getStatut() === true){
                        $groupe_encour[] = $groupebrief;
                    }
                    $grpe->getBrief()->removeGroupe($groupebrief);
                }
                foreach ($groupe_encour as $grpeEncours){
                    $GroupeBrief = $grpeEncours->getBriefs()->getValues();
                    foreach ($GroupeBrief as $grpeBrief){
                        if($grpeBrief->getId() == $brief_id){
                            $grpe->getBrief()->addGroupe($grpeEncours);
                        }
                    }
                }
                if($grpe->getGroupe()->getPromo()->getId() == $id){
                    $brief[] = $grpe;
                }
            }
            return $brief;
        }
        else{
            return $this->json("Erreur", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route(name="listebriefbyformateur",
     *   path="api/formateurs/promos/{id}/briefs",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\BriefController::showBriefByFormateur",
     *     "_api_resource_class"=Brief::class,
     *     "_api_collection_operation_name"="getbriefbyformateur",
     *    }
     * )
     * @param $id
     * @param BriefGroupeRepository $repo_groupebrief
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param PromoBriefRepository $repo_promoBrief
     * @param BriefRepository $repo_brief
     * @param PromoRepository $repo_promo
     * @param GroupeRepository $repo_groupe
     * @param FormateurRepository $repository
     * @param PromoBriefRepository $repo_promobrief
     * @param BriefGroupeRepository $repo_briefgroupe
     * @return PromoBrief[]
     */

    public function showBriefByFormateur($id, BriefGroupeRepository $repo_groupebrief, SerializerInterface $serializer,
                                            Request $request, PromoBriefRepository $repo_promoBrief, BriefRepository $repo_brief,  PromoRepository $repo_promo,
                                         GroupeRepository $repo_groupe, FormateurRepository $repository,
                                         PromoBriefRepository $repo_promobrief,
                                         BriefGroupeRepository $repo_briefgroupe){
      /*  $briefGroupe = $repo_groupebrief->findAll();
        $briefPromo = $repo_promoBrief->findBy(['promo'=>$id]);
        $briefs = [];
        $token = substr($request->server->get("HTTP_AUTHORIZATION"), 7);
        $token = explode(".", $token);
        $playload = $token[1];
        $playload = json_decode(base64_decode($playload));
        $email = $playload->username;
        foreach ($briefPromo as $brief){
            if($email === $brief->getBrief()->getFormateur()->getEmail()){
                $promo = $brief->getBrief()->getPromobrief();
                foreach ($promo as $promos){
                    if($id == $promos->getPromo()->getId()){
                        $groupes = $brief->getBrief()->getGroupe()->getValues();
                        foreach ($groupes as $grpe)
                        dd($brief->getBrief()->getGroupe()->getValues());
                        $briefs[] = $brief;
                    }
                }
            }
        }
        return $briefs;*/

        $groupe = "briefGroupe";
        $briefs = $this->showBriefByGroupe($repo_brief, $id,$repo_promo, $repo_groupe, $groupe, $repository,$request,
                                            $repo_promobrief, $serializer,$repo_briefgroupe);
        $tabBrief = [];
        $idBrief = 0;
        /*
        foreach ($briefs as $brief){
            $id_brief = $brief->getBrief()->getId();
           // dd($brief->getBrief()->getId());
                if(empty($tabBrief)){
                    $tabBrief[] = $brief;
                }else{
                    foreach ($tabBrief as $tabBr){
                        if($tabBr->getBrief()->getId() === $id_brief){
                            $tabBrief[] = $brief;
                        }
                      //  dd($tabBr->getBrief()->getId());
                    }
                }
            $idBrief = $brief->getBrief()->getId();
        }
        */
        return $briefs;
    }

    /**
     * @Route(name="listebriefbroullonsformateurs",
     *   path="api/formateurs/{id}/briefs/broullons",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\BriefController::showBriefBroullonByFormateur",
     *     "_api_resource_class"=Brief::class,
     *     "_api_collection_operation_name"="getbriefbroullonsbyformateur",
     *    }
     * )
     * @param $id
     * @param FormateurRepository $repoFormateur
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param BriefRepository $repoBrief
     * @param $broullon
     * @return Brief[]
     */

    public function showBriefBroullonByFormateur($id, FormateurRepository $repoFormateur, SerializerInterface $serializer,
                                                    Request $request, BriefRepository $repoBrief, $broullon = 'broullon'){

        $token = substr($request->server->get("HTTP_AUTHORIZATION"), 7);
        $token = explode(".", $token);
        $playload = $token[1];
        $playload = json_decode(base64_decode($playload));
        $email = $playload->username;
        $formateur = $repoFormateur->find($id);
        if ($email === $formateur->getEmail()){
            $briefs = $repoBrief->findBy(['formateur'=>$id, 'statutBrief'=>$broullon]);
           // return $this->json($briefs, Response::HTTP_OK);
            return $briefs;
        }
        else{
            return $this->json("Acces Interdire", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route(name="listebriefvalideformateurs",
     *   path="api/formateurs/{id}/briefs/valide",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\BriefController::showBriefValideByFormateur",
     *     "_api_resource_class"=Brief::class,
     *     "_api_collection_operation_name"="getbriefValidebyformateur",
     *    }
     * )
     * @param $id
     * @param FormateurRepository $repoFormateur
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param BriefRepository $repoBrief
     * @param string $broullon
     * @return Brief[]
     */
    public function showBriefValideByFormateur($id, FormateurRepository $repoFormateur, SerializerInterface $serializer,
                                               Request $request, BriefRepository $repoBrief, $broullon = 'valide'){

        $briefs = $this->showBriefBroullonByFormateur($id, $repoFormateur,$serializer,$request, $repoBrief, $broullon);
        return $briefs;
    }

    /**
     * @Route(name="listebriefApprenant",
     *   path="api/apprenants/promos/{id}/briefs",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\BriefController::showBriefApprenant",
     *     "_api_resource_class"=Brief::class,
     *     "_api_collection_operation_name"="getbriefApprenant",
     *    }
     * )
     * @param $id
     * @param BriefGroupeRepository $repoPromoBrief
     * @param PromoRepository $repoPromo
     * @return Brief
     */
    public function showBriefApprenant($id, BriefGroupeRepository $repoPromoBrief, PromoRepository $repoPromo){
       $brief = $repoPromoBrief->findAll();
        $promo = $repoPromo->find($id);
       foreach ($brief as $br){
           if($br->getGroupe()->getPromo()->getId() == $id){
               return $br->getBrief();
           }
       }
      //  dd($promo->getGroupe());
        $briefdesApprenants = [];
        $briefAssignerApprenant = [];
        /*
        foreach ($brief as $briefs){
            $briefApprenant = $briefs->getPromoBriefApprenants()->getValues();
            foreach ($briefApprenant as $brApprenant){
                if($brApprenant->getStatut() !== "assigner"){
                    $briefs->removePromoBriefApprenant($brApprenant);
                }else{
                    if(empty($briefAssignerApprenant)){
                        $briefAssignerApprenant[] = $briefs->getBrief();
                    }
                    else{
                        $presence = false;
                        foreach ($briefAssignerApprenant as $brApp){
                                if($brApp->getId() === $briefs->getId()){
                                   $presence = true;
                                   break;
                                }
                        }
                        if($presence === false){
                            $briefAssignerApprenant[] = $briefs->getBrief();
                        }
                    }
                }
            }
        }
        foreach ($briefAssignerApprenant as $briefAssigner){
            $groupe = $briefAssigner->getGroupe()->getValues();
            foreach ($groupe as $grpe){
                if($grpe->getStatut() !== true ){
                    $briefAssigner->getGroupe()->removeElement($grpe);
                }
            }
        }
        return $briefAssignerApprenant;

        */

    }

    /**
     * @Route(name="listepromobrief",
     *   path="api/formateurs/promo/{id}/briefs/{brief}",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\BriefController::showBriefPromo",
     *     "_api_resource_class"=Brief::class,
     *     "_api_collection_operation_name"="getpromobrief",
     *    }
     * )
     * @param $id
     * @param $brief
     * @param PromoBriefRepository $repoPromoBrief
     * @return Brief
     */

    public function showBriefPromo($id,$brief,PromoBriefRepository $repoPromoBrief){

        $promoBriefs = $repoPromoBrief->findOneBy(["promo"=>$id,"brief"=>$brief]);
        return $promoBriefs->getBrief();
      //  dd($promoBriefs->getBrief());
        $briefs = [];
        /*
        foreach ($promoBriefs as $promobrief){
            $groupes = $promobrief->getBrief()->getGroupe()->getValues();
            foreach ($groupes as $grpe){
                if($grpe->getStatut() !== true ){
                    $promobrief->getBrief()->getGroupe()->removeElement($grpe);
                }
            }
            $briefs[] =  $promobrief->getBrief();
        }
        return $briefs;
        */
    }


    /**
     * @Route(name="listebriefApprenantById",
     *   path="api/apprenants/promo/{id}/briefs/{brief}",
     *   methods={"GET"},
     *   defaults={
     *     "_controller"="\app\BriefController::showBriefApprenantById",
     *     "_api_resource_class"=Brief::class,
     *     "_api_collection_operation_name"="getbriefApprenantById",
     *    }
     * )
     * @param $id
     * @param BriefGroupeRepository $repoPromoBrief
     * @param PromoRepository $repoPromo
     * @param $brief
     * @return Brief
     */
    public function showBriefApprenantById($id, BriefGroupeRepository $repoPromoBrief, PromoRepository $repoPromo, $brief){
        $apprenantBrief = $this->showBriefApprenant($id, $repoPromoBrief, $repoPromo);
            if($apprenantBrief->getId() == $brief){
                return $apprenantBrief;
            }
            else{
                return $this->json("Erreur",Response::HTTP_BAD_REQUEST);
            }

    }
}




