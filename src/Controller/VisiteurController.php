<?php

namespace App\Controller;

use App\Entity\Visiteur;
use App\Form\VisiteurType;
use App\Repository\VisiteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/visiteur')]
final class VisiteurController extends AbstractController{
    #[Route(name: 'visiteur_index', methods: ['GET'])]
    public function index(VisiteurRepository $visiteurRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // Nombre de visiteurs par page
        $pagination = $paginator->paginate(
            $visiteurRepository->findAllQuery(), // La requête pour obtenir les visiteurs
            $request->query->getInt('page', 1), // Page actuelle, par défaut la première
            9 // Nombre d'éléments par page
        );

        return $this->render('visiteur/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_visiteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $visiteur = new Visiteur();
    $form = $this->createForm(VisiteurType::class, $visiteur);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérification de l'unicité de l'email
        $existingVisiteur = $entityManager->getRepository(Visiteur::class)->findOneBy(['email' => $visiteur->getEmail()]);
        
        if ($existingVisiteur) {
            $this->addFlash('error', 'L\'email est déjà utilisé. Veuillez en choisir un autre.');
            return $this->redirectToRoute('app_visiteur_new');
        }

        $entityManager->persist($visiteur);
        $entityManager->flush();

        return $this->redirectToRoute('visiteur_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('visiteur/new.html.twig', [
        'visiteur' => $visiteur,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_visiteur_show', methods: ['GET'])]
    public function show(Visiteur $visiteur): Response
    {
        return $this->render('visiteur/show.html.twig', [
            'visiteur' => $visiteur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_visiteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Visiteur $visiteur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VisiteurType::class, $visiteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('visiteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('visiteur/edit.html.twig', [
            'visiteur' => $visiteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_visiteur_delete', methods: ['POST'])]
    public function delete(Request $request, Visiteur $visiteur, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le token CSRF depuis la requête POST
        $csrfToken = $request->request->get('_token');
    
        // Vérifier si le token est valide
        if ($this->isCsrfTokenValid('delete' . $visiteur->getId(), $csrfToken)) {
            $entityManager->remove($visiteur);
            $entityManager->flush();
    
            $this->addFlash('success', 'Le visiteur a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Le token CSRF est invalide.');
        }
    
        return $this->redirectToRoute('visiteur_index', [], Response::HTTP_SEE_OTHER);
    }
}

