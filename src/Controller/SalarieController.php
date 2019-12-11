<?php

namespace App\Controller;

use App\Entity\Salarie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/salaries")
 */
class SalarieController extends AbstractController
{
    /**
     * @Route("/", name="salarie_index")
     */
    public function index()
    {
        $salaries = $this->getDoctrine()
                ->getRepository(Salarie::class)
                ->getAll();

        return $this->render('salarie/index.html.twig', [
            'salaries' => $salaries,
        ]);
    }

    /**
     * @Route("/{id}", name="salarie_show", method="GET")
     */
    public function show(Salarie $salarie): Response {
        return $this->render('Salarie/show.html.twig', ['salarie' => $salarie]);
    }
}
