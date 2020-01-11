<?php

namespace App\Controller;

use App\Repository\OfficineRepository;
use App\Repository\OperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class JournalController
 * @Route("/journal")
 */
class JournalController extends AbstractController
{
    /**
     * @Route("/", name="journal", methods={"GET","POST"})
     */
    public function index(Request $request, OperationRepository $operationRepository, OfficineRepository $officineRepository)
    {
        $reqOfficine = $request->get('officine');
        $debut = $request->get('date_debut');
        $fin = $request->get('date_fin');

        if ($reqOfficine){
            $operations = $operationRepository->findJournal($reqOfficine,$debut,$fin);
            $officine = $reqOfficine;
        }elseif ($debut and $fin){
            $operations = $operationRepository->findJournal(null,$debut,$fin);
            $officine = null;
        }else{
            $operations = $operationRepository->findJournal();
            $officine = null;
            $debut = date('Y-m-01');
            $fin = date('Y-m-d');
        }
        return $this->render('journal/index.html.twig', [
            'operations' => $operations,
            'officines' => $officineRepository->findAll(),
            'date_debut' => $debut,
            'date_fin' => $fin,
            'officine' => $officine,
            'current_menu' => 'journal'
        ]);
    }
}
