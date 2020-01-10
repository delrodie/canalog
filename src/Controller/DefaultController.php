<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use App\Repository\FournisseurRepository;
use App\Repository\OfficineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(CommandeRepository $commandeRepository, FournisseurRepository $fournisseurRepository)
    {
        return $this->render('default/index.html.twig', [
            'current_menu' => 'accueil',
            'commandes' => $commandeRepository->findByASC(),
            'commande_montant' => $commandeRepository->getMontant(),
            'fournisseurs' => $fournisseurRepository->findAll(),
        ]);
    }

    /**
     * @param OfficineRepository $officineRepository
     * @Route("/menu", name="app_default_menu")
     */
    public function menu(OfficineRepository $officineRepository) : Response
    {
        return $this->render("default/menu.html.twig",['menus'=>$officineRepository->findByASC()]);
    }
}
