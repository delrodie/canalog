<?php

namespace App\Controller;

use App\Repository\OfficineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'current_menu' => 'accueil'
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
