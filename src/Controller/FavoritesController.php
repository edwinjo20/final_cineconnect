<?php   
namespace App\Controller;

use App\Entity\Favorites;
use App\Repository\FavoritesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/favorites', name: 'api_favorites')]
class FavoritesController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'get_all_favorites', methods: ['GET'])]
    public function getAll(FavoritesRepository $favoritesRepository): JsonResponse
    {
        $favorites = $favoritesRepository->findAll();
        return $this->json($favorites, 200, [], ['groups' => 'favorites:read']);
    }

    #[Route('', name: 'create_favorite', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $favorite = new Favorites();
        $favorite->setUser($this->getUser());
        $favorite->setFilm($data['film']);

        $this->entityManager->persist($favorite);
        $this->entityManager->flush();

        return $this->json(['message' => 'Favorite added successfully'], 201);
    }

    #[Route('/{id}', name: 'delete_favorite', methods: ['DELETE'])]
    public function delete(Favorites $favorite): JsonResponse
    {
        $this->entityManager->remove($favorite);
        $this->entityManager->flush();

        return $this->json(['message' => 'Favorite removed successfully']);
    }
}
