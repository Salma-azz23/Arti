<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Form\ArtisteType;
use App\Repository\ArtisteRepository;
use App\Repository\OeuvreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/artiste')]
class ArtisteController extends AbstractController
{
    #[Route('/', name: 'artiste_index', methods: ['GET'])]
    public function index(Request $request, ArtisteRepository $artisteRepository, OeuvreRepository $oeuvreRepository, PaginatorInterface $paginator): Response
    {
        $searchTerm = $request->query->get('rechercher');
    
        
            $pagination = $paginator->paginate(
                $artisteRepository->findAll(), // ou votre QueryBuilder
                $request->query->getInt('page', 1), // Numéro de la page
                5 // Nombre d'éléments par page
            );
        
    
        return $this->render('artiste/index.html.twig', [
            'artistes' => $pagination,
        ]);
    }

    #[Route('/new', name: 'artiste_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $artiste = new Artiste();
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();
            
            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'), // Défini dans services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('artiste_new');
                }

                $artiste->setPhoto($newFilename);
            }

            $entityManager->persist($artiste);
            $entityManager->flush();

            return $this->redirectToRoute('artiste_index');
        }

        return $this->render('artiste/new.html.twig', [
            'artiste' => $artiste,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'artiste_show', methods: ['GET'])]
    public function show(Artiste $artiste): Response
    {
        return $this->render('artiste/show.html.twig', [
            'artiste' => $artiste,
        ]);
    }

    #[Route('/{id}/edit', name: 'artiste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Artiste $artiste, EntityManagerInterface $entityManager): Response
    {
        // Conservez la photo actuelle avant de traiter le formulaire
        $currentPhoto = $artiste->getPhoto();
    
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();
    
            if ($photoFile) {
                // Supprimez l'ancienne photo si elle existe
                if ($currentPhoto) {
                    $oldPhotoPath = $this->getParameter('photos_directory') . '/' . $currentPhoto;
                    if (file_exists($oldPhotoPath)) {
                        try {
                            unlink($oldPhotoPath);
                        } catch (\Exception $e) {
                            $this->addFlash('danger', 'Erreur lors de la suppression de l\'ancienne photo.');
                        }
                    }
                }
    
                // Générez un nouveau nom de fichier unique
                $newFilename = uniqid('', true) . '.' . $photoFile->guessExtension();
    
                try {
                    // Déplacez le fichier téléchargé dans le répertoire défini
                    $photoFile->move($this->getParameter('photos_directory'), $newFilename);
                    $artiste->setPhoto($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Erreur lors du téléchargement de la nouvelle photo.');
                    return $this->redirectToRoute('artiste_edit', ['id' => $artiste->getId()]);
                }
            } else {
                // Si aucune nouvelle photo n'est uploadée, conserver la photo actuelle
                $artiste->setPhoto($currentPhoto);
            }
    
            // Sauvegarde des modifications dans la base de données
            $entityManager->flush();
    
            $this->addFlash('success', 'Les modifications ont été enregistrées avec succès.');
            return $this->redirectToRoute('artiste_index');
        }
    
        return $this->render('artiste/edit.html.twig', [
            'artiste' => $artiste,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}/delete', name: 'app_artiste_delete', methods: ['POST'])]
    public function delete(Request $request, Artiste $artiste, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $artiste->getId(), $request->request->get('_token'))) {
            $entityManager->remove($artiste);
            $entityManager->flush();
        }

        return $this->redirectToRoute('artiste_index');
    }
}
