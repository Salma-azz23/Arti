<?php

namespace App\Controller;

use App\Entity\Oeuvre;
use App\Form\OeuvreType;
use App\Repository\OeuvreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/oeuvre')]
class OeuvreController extends AbstractController
{
    #[Route('/', name: 'oeuvre_index', methods: ['GET'])]
    public function index(Request $request, OeuvreRepository $oeuvreRepository): Response
    {
        $searchTerm = $request->query->get('search', ''); // Récupère la recherche depuis l'URL
        $oeuvres = $searchTerm
            ? $oeuvreRepository->findByArtisteName($searchTerm) // Cherche selon l'artiste
            : $oeuvreRepository->findAll(); // Liste complète si pas de recherche

            
    
        return $this->render('oeuvre/index.html.twig', [
            'oeuvres' => $oeuvres,
            'searchTerm' => $searchTerm, // Pour conserver la saisie dans la barre de recherche
        ]);
    }
    

    #[Route('/new', name: 'app_oeuvre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $oeuvre = new Oeuvre();
        $form = $this->createForm(OeuvreType::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('oeuvre_new');
                }

                $oeuvre->setPhoto($newFilename);
            }

            $entityManager->persist($oeuvre);
            $entityManager->flush();

            return $this->redirectToRoute('oeuvre_index');
        }

        return $this->render('oeuvre/new.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_oeuvre_show', methods: ['GET'])]
    public function show(Oeuvre $oeuvre): Response
    {
        return $this->render('oeuvre/show.html.twig', [
            'oeuvre' => $oeuvre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_oeuvre_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Oeuvre $oeuvre, EntityManagerInterface $entityManager): Response
{
    // Sauvegarder la photo actuelle avant de traiter le formulaire
    $currentPhoto = $oeuvre->getPhoto();

    $form = $this->createForm(OeuvreType::class, $oeuvre);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $photoFile = $form->get('photo')->getData();

        if ($photoFile) {
            // Supprimer l'ancienne photo si elle existe
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

            // Générer un nouveau nom de fichier unique pour la nouvelle photo
            $newFilename = uniqid('', true) . '.' . $photoFile->guessExtension();

            try {
                // Déplacer la photo téléchargée vers le répertoire défini
                $photoFile->move($this->getParameter('photos_directory'), $newFilename);
                $oeuvre->setPhoto($newFilename);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur lors du téléchargement de la nouvelle photo.');
                return $this->redirectToRoute('app_oeuvre_edit', ['id' => $oeuvre->getId()]);
            }
        } else {
            // Si aucune nouvelle photo n'est uploadée, conserver la photo actuelle
            $oeuvre->setPhoto($currentPhoto);
        }

        // Enregistrer les modifications dans la base de données
        $entityManager->flush();

        $this->addFlash('success', 'Les modifications ont été enregistrées avec succès.');
        return $this->redirectToRoute('oeuvre_index');
    }

    return $this->render('oeuvre/edit.html.twig', [
        'oeuvre' => $oeuvre,
        'form' => $form->createView(),
    ]);
}


    #[Route('/{id}/delete', name: 'app_oeuvre_delete', methods: ['POST'])]
    public function delete(Request $request, Oeuvre $oeuvre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $oeuvre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($oeuvre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('oeuvre_index');
    }
    
    
}
