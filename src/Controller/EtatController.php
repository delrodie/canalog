<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use App\Repository\FournisseurRepository;
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
}
