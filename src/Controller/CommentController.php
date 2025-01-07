<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/comments', name: 'api_comments')]
class CommentController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'get_all_comments', methods: ['GET'])]
    public function getAll(CommentRepository $commentRepository): JsonResponse
    {
        $comments = $commentRepository->findAll();
        return $this->json($comments, 200, [], ['groups' => 'comment:read']);
    }

    #[Route('', name: 'create_comment', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $comment = new Comment();
        $comment->setContent($data['content']);
        $comment->setDate(new \DateTime());

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return $this->json(['message' => 'Comment created successfully'], 201);
    }

    #[Route('/{id}', name: 'update_comment', methods: ['PUT'])]
    public function update(Request $request, Comment $comment): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $comment->setContent($data['content']);

        $this->entityManager->flush();

        return $this->json(['message' => 'Comment updated successfully']);
    }

    #[Route('/{id}', name: 'delete_comment', methods: ['DELETE'])]
    public function delete(Comment $comment): JsonResponse
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        return $this->json(['message' => 'Comment deleted successfully']);
    }
}
