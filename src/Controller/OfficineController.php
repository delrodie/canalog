<?php

namespace App\Controller;

use App\Entity\Officine;
use App\Form\OfficineType;
use App\Repository\OfficineRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/officine")
 */
class OfficineController extends AbstractController
{
    /**
     * @Route("/", name="officine_index", methods={"GET","POST"})
     */
    public function index(Request $request,OfficineRepository $officineRepository): Response
    {
        $officine = new Officine();
        $form = $this->createForm(OfficineType::class, $officine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $entityManager = $this->getDoctrine()->getManager();
            $officine->setSlug($slugify->slugify($officine->getNom()));
            $entityManager->persist($officine);
            $entityManager->flush();

            return $this->redirectToRoute('officine_index');
        }

        return $this->render('officine/index.html.twig', [
            'officines' => $officineRepository->findByASC(),
            'officine' => $officine,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="officine_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $officine = new Officine();
        $form = $this->createForm(OfficineType::class, $officine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($officine);
            $entityManager->flush();

            return $this->redirectToRoute('officine_index');
        }

        return $this->render('officine/new.html.twig', [
            'officine' => $officine,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="officine_show", methods={"GET"})
     */
    public function show(Officine $officine): Response
    {
        return $this->render('officine/show.html.twig', [
            'officine' => $officine,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="officine_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Officine $officine, OfficineRepository $officineRepository): Response
    {
        $form = $this->createForm(OfficineType::class, $officine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $officine->setSlug($slugify->slugify($officine->getNom()));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('officine_index');
        }

        return $this->render('officine/edit.html.twig', [
            'officine' => $officine,
            'form' => $form->createView(),
            'officines' => $officineRepository->findByASC(),
        ]);
    }

    /**
     * @Route("/{id}", name="officine_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Officine $officine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$officine->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($officine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('officine_index');
    }
}
