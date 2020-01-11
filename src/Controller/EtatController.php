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
 * Class EtatController
 * @Route("/etat")
 */
class EtatController extends AbstractController
{
    /**
     * @Route("/", name="etat_commande", methods={"GET","POST"})
     */
    public function commande(Request $request, CommandeRepository $commandeRepository, FournisseurRepository $fournisseurRepository)
    {
        $fseur = $request->get('fseur'); //dd($fseur);
        $debut = $request->get('debutPeriode'); //dd($debut);
        $fin = $request->get('finPeriode');
        if ($fseur==='aucun'){
            $commandes = $commandeRepository->findBySearch($fseur=null,$debut,$fin);
            $montant = $commandeRepository->getMontant($fseur=null,$debut,$fin);
        }else{
            $commandes = $commandeRepository->findBySearch($fseur,$debut,$fin);
            $montant = $commandeRepository->getMontant($fseur,$debut,$fin);
        }
        return $this->render('etat/etat_commande.html.twig', [
            'commandes' => $commandes,
            'commande_montant' => $montant,
            'date_debut' => $debut,
            'date_fin' => $fin
        ]);
    }

    /**
     * @Route("/{type}", name="etat_operation", methods={"GET","POST"})
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

        // Si la periode n'a pas été definie
        if (!$debut) $debut=date('Y-m-01');
        if (!$fin) $fin=date('Y-m-d');

        return $this->render("etat/etat_operation.html.twig",[
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
