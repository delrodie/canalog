<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use App\Repository\FournisseurRepository;
use App\Repository\ModeRepository;
use App\Repository\OfficineRepository;
use App\Repository\OperationRepository;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 * @Route("/search")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/", name="search_commande", methods={"GET","POST"})
     */
    public function commande(Request $request, CommandeRepository $commandeRepository, FournisseurRepository $fournisseurRepository)
    {
        $fseur = $request->get('fseur'); //dd($fseur);
        $debut = $request->get('debutPeriode');
        $fin = $request->get('finPeriode');
        $commandes = $commandeRepository->findBySearch($fseur,$debut,$fin);
        $montant = $commandeRepository->getMontant($fseur,$debut,$fin);
        if (!$fseur) $fseur = 'aucun';
        return $this->render('search/search_commande.html.twig', [
            'current_menu' => 'commande',
            'commandes' => $commandes,
            'commande_montant' => $montant,
            'fournisseurs' => $fournisseurRepository->findAll(),
            'fseur' => $fseur,
            'date_debut' =>$debut,
            'date_fin' =>$fin
        ]);
    }

    /**
     * @Route("/{type}", name="search_operation", methods={"GET","POST"})
     */
    public function operation(Request $request, $type, OperationRepository $operationRepository, TypeRepository $typeRepository, ModeRepository $modeRepository, OfficineRepository $officineRepository)
    {
        $reqOfficine = $request->get('officine'); //dd($reqOfficine);
        $reqMode = $request->get('mode'); //dd($reqMode);
        $debut = $request->get('date_debut'); //dd($debut);
        $fin = $request->get('date_fin'); //dd($fin);
        //$deleted = 'officine '.$reqOfficine.' - mode: '.$reqMode.' - debut: '.$debut.' - fin: '.$fin; dd($deleted);
        $typeEntity = $typeRepository->findByLibelle($type);

        if ($reqOfficine and $reqMode){
            $operations = $operationRepository->findBySearch($type,$reqOfficine,$reqMode,$debut,$fin);
            $montant = $operationRepository->getMontantBySearch($type,$reqOfficine,$reqMode,$debut,$fin);
        }elseif ($reqOfficine){
            $operations = $operationRepository->findBySearch($type,$reqOfficine,null,$debut,$fin);
            $montant = $operationRepository->getMontantBySearch($type,$reqOfficine,null,$debut,$fin);
        }elseif ($reqMode){
            $operations = $operationRepository->findBySearch($type,null,$reqMode,$debut,$fin);
            $montant = $operationRepository->getMontantBySearch($type,null,$reqMode,$debut,$fin);
        }elseif ($debut and  $fin){
            $operations = $operationRepository->findBySearch($type,null,null,$debut,$fin);
            $montant = $operationRepository->getMontantBySearch($type,null,null,$debut,$fin);
        }else{
            $operations = $operationRepository->findBySearch($type);
            $montant = $operationRepository->getMontantBySearch($type);
        }
        // rechercher selon le mode
        // rechercher selon la période  si periode n'est definie alors consideren le mois en cours

        return $this->render("search/search_operation.html.twig",[
            'officine' => $reqOfficine,
            'mode' => $reqMode,
            'date_debut'=> $debut,
            'date_fin' => $fin,
            'officines' => $officineRepository->findByASC(),
            'operations' => $operations,
            'modes' => $modeRepository->findAll(),
            'type_montant' => $montant,
            'type' => $type,
            'current_menu'=>$type
        ]);
    }
}
