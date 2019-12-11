<?php

namespace App\Controller;

use App\Entity\Entreprise;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/entreprises")
 */
class EntrepriseController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/add", name="entreprise_add")
     * @Route("/{id}/edit", name="entreprise_edit")
     */
    public function addEntreprise(Request $request, Entreprise $entreprise = null)
    {
        if(!$entreprise){
            $entreprise = new Entreprise();
        }
        $form = $this->createFormBuilder($entreprise)
            ->add('raisonSociale', TextType::class)
            ->add('siret', TextType::class)
            ->add('adresse', TextType::class)
            ->add('cp', TextType::class)
            ->add('ville', TextType::class)
            ->add('Valider', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($entreprise);
            $this->entityManager->flush();

            return $this->redirectToRoute('entreprise_index');
        }

        return $this->render('entreprise/add_edit.html.twig', [
            'form' => $form->createView(),
            'editMode' => $entreprise->getId() !== null
        ]);
    }

    /**
     * @Route("/{id}/remove", name="entreprise_remove")
     */
    public function removeEntreprise($id)
    {
        $adminEntreprises = $this->entityManager->getRepository(Entreprise::class)
                                             ->find($id);
        $this->entityManager->remove($adminEntreprises);
        $this->entityManager->flush();                                                     
        return $this->redirectToRoute('entreprise_index');
    }
    
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
