<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Film;
use App\Entity\User;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/reviews', name: 'api_reviews')]
class ReviewController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // ✅ GET All Reviews
    #[Route('', name: 'get_all_reviews', methods: ['GET'])]
    public function getAll(ReviewRepository $reviewRepository): JsonResponse
    {
        $reviews = $reviewRepository->findAll();

        return $this->json($reviews, 200, [], ['groups' => 'review:read']);
    }

   // ✅ POST Create a New Review
#[Route('', name: 'create_review', methods: ['POST'])]
public function create(Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    // Validate the required fields
    if (empty($data['content']) || empty($data['ratingGiven']) || empty($data['filmId']) || empty($data['userId'])) {
        return $this->json(['message' => 'Missing required fields'], 400);
    }

    // Find the film and user by their IDs
    $film = $this->entityManager->getRepository(Film::class)->find($data['filmId']);
    $user = $this->entityManager->getRepository(User::class)->find($data['userId']);

    if (!$film) {
        return $this->json(['message' => 'Film not found'], 404);
    }

    if (!$user) {
        return $this->json(['message' => 'User not found'], 404);
    }

    // Create a new Review object
    $review = new Review();
    $review->setContent($data['content']);
    $review->setRatingGiven($data['ratingGiven']);
    $review->setPublicationDate(new \DateTime());
    $review->setFilm($film);
    $review->setUser($user);

    // Save the review to the database
    $this->entityManager->persist($review);
    $this->entityManager->flush();

    return $this->json(['message' => 'Review created successfully'], 201);
}


    // ✅ PUT Update a Review
    #[Route('/{id}', name: 'update_review', methods: ['PUT'])]
    public function update(Request $request, Review $review): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!empty($data['content'])) {
            $review->setContent($data['content']);
        }

        if (!empty($data['ratingGiven'])) {
            $review->setRatingGiven($data['ratingGiven']);
        }

        $this->entityManager->flush();

        return $this->json(['message' => 'Review updated successfully']);
    }

    // ✅ DELETE a Review
    #[Route('/{id}', name: 'delete_review', methods: ['DELETE'])]
    public function delete(Review $review): JsonResponse
    {
        $this->entityManager->remove($review);
        $this->entityManager->flush();

        return $this->json(['message' => 'Review deleted successfully']);
    }
}
