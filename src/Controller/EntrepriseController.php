<?php

namespace App\Controller;

use App\Entity\Entreprise;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/entreprises")
 */
class EntrepriseController extends AbstractController
{
    /**
     * @Route("/", name="entreprise_index")
     */
    public function index()
    {
        $entreprises = $this->getDoctrine()
                ->getRepository(Entreprise::class)
                ->getAll();

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }

    /**
     * @Route("/{id}", name="entreprise_show", methods={"GET"})
     */
    public function show(Entreprise $entreprise): Response {
        return $this->render('Entreprise/show.html.twig', ['entreprise' => $entreprise]);
    }
}
