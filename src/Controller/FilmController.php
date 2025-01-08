<?php

namespace App\Controller;

use App\Entity\Film;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FilmController extends AbstractController
{
    // Public Endpoint: No login required to view the list of films
    #[Route('/api/films', name: 'api_films', methods: ['GET'])]
    public function index(FilmRepository $filmRepository): JsonResponse
    {
        $films = $filmRepository->findAll();
    
        $data = [];
        foreach ($films as $film) {
            $data[] = [
                'id' => $film->getId(),
                'title' => $film->getTitle(),
                'description' => $film->getDescription(),
                'releaseDate' => $film->getReleaseDate()->format('Y-m-d'),
                'genre' => $film->getGenre(),
                'imagePath' => $film->getImagePath(),  // ✅ Use the database value
            ];
        }
    
        return $this->json($data);
    }

    // Protected Endpoint: Admin required to add a film
    #[Route('/api/films/create', name: 'api_films_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $film = new Film();
        $film->setTitle($data['title']);
        $film->setDescription($data['description']);
        $film->setReleaseDate(new \DateTime($data['releaseDate']));
        $film->setGenre($data['genre']);
        $film->setImagePath($data['imagePath'] ?? null);

        $em->persist($film);
        $em->flush();

        return $this->json(['message' => 'Film added successfully'], 201);
    }

    // Protected Endpoint: Admin required to update a film
    #[Route('/api/films/{id}', name: 'api_films_update', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(int $id, Request $request, FilmRepository $filmRepository, EntityManagerInterface $em): JsonResponse
    {
        $film = $filmRepository->find($id);

        if (!$film) {
            return $this->json(['message' => 'Film not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $film->setTitle($data['title']);
        $film->setDescription($data['description']);
        $film->setReleaseDate(new \DateTime($data['releaseDate']));
        $film->setGenre($data['genre']);
        $film->setImagePath($data['imagePath'] ?? null);

        $em->flush();

        return $this->json(['message' => 'Film updated successfully']);
    }

    // Protected Endpoint: Admin required to delete a film
    #[Route('/api/films/{id}', name: 'api_films_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id, FilmRepository $filmRepository, EntityManagerInterface $em): JsonResponse
    {
        $film = $filmRepository->find($id);

        if (!$film) {
            return $this->json(['message' => 'Film not found'], 404);
        }

        $em->remove($film);
        $em->flush();

        return $this->json(['message' => 'Film deleted successfully']);
    }
}
