<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use App\Repository\FournisseurRepository;
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
}
