<?php 

// src/Controller/StatisticsController.php
namespace App\Controller;

use App\Entity\Artiste;
use App\Entity\Oeuvre;
use App\Entity\Visiteur;
use App\Repository\ArtisteRepository;
use App\Repository\OeuvreRepository;
use App\Repository\VisiteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiquesController extends AbstractController
{
    #[Route('/statistiques', name: 'app_statistiques')]
    public function index(
        ArtisteRepository $artisteRepository,
        OeuvreRepository $oeuvreRepository,
        VisiteurRepository $visiteurRepository
    ): Response {
        // Récupérer les données
        $nombreArtistes = $artisteRepository->count([]);
        $nombreOeuvres = $oeuvreRepository->count([]);
        $nombreVisiteurs = $visiteurRepository->count([]);

        $oeuvresParArtiste = $oeuvreRepository->countOeuvresByArtist();

        $topArtistes = [
            ['nom' => 'Artiste A', 'contribution' => 20],
            ['nom' => 'Artiste B', 'contribution' => 15],
            ['nom' => 'Artiste C', 'contribution' => 10]
        ];

        // Passer les données à la vue
        return $this->render('statistiques/index.html.twig', [
            'nombreArtistes' => $nombreArtistes,
            'nombreOeuvres' => $nombreOeuvres,
            'nombreVisiteurs' => $nombreVisiteurs,
            'oeuvresParArtiste' => $oeuvresParArtiste,

            'topArtistes' => $topArtistes,
        ]);
    }
}
