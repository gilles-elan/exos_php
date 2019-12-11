<?php

namespace App\Controller;

use App\Entity\Salarie;
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
 * @Route("/salaries")
 */
class SalarieController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/add", name="salarie_add")
     * @Route("/{id}/edit", name="salarie_edit")
     */
    public function addSalarie(Request $request, Salarie $salarie = null)
    {
        if(!$salarie){
            $salarie = new Salarie();
        }
        $form = $this->createFormBuilder($salarie)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('dateNaissance', DateType::class, [
                'years' => range(date('Y'),date('Y')-70),
                'label' => 'Date de naissance',
                'format' => 'ddMMMMyyyy'
            ])
            ->add('adresse', TextType::class)
            ->add('cp', TextType::class)
            ->add('ville', TextType::class)
            ->add('dateEmbauche', DateType::class, [
                'years' => range(date('Y'),date('Y')-70),
                'label' => 'Date d\'embauche',
                'format' => 'ddMMMMyyyy'
            ])
            ->add('Entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'raisonSociale'
            ])
            ->add('Valider', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($salarie);
            $this->entityManager->flush();

            return $this->redirectToRoute('salarie_index');
        }

        return $this->render('salarie/add_edit.html.twig', [
            'form' => $form->createView(),
            'editMode' => $salarie->getId() !== null
        ]);
    }

    /**
     * @Route("/{id}/remove", name="salarie_remove")
     */
    public function removeSalarie($id)
    {
        $adminSalaries = $this->entityManager->getRepository(Salarie::class)
                                             ->find($id);
        $this->entityManager->remove($adminSalaries);
        $this->entityManager->flush();                                                     
        return $this->redirectToRoute('salarie_index');
    }

    /**
     * @Route("/", name="salarie_index")
     */
    public function index(): Response
    {
        $salaries = $this->getDoctrine()
                ->getRepository(Salarie::class)
                ->findAll();

        return $this->render('salarie/index.html.twig', [
            'salaries' => $salaries,
        ]);
    }

    /**
     * @Route("/{id}", name="salarie_show", methods={"GET"})
     */
    public function show(Salarie $salarie): Response {
        return $this->render('salarie/show.html.twig', [
            'salarie' => $salarie
        ]);
    }
}
