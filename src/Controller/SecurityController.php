<?php
namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityController extends AbstractController
{
    #[Route('/api/login_check', name: 'api_login', methods: ['POST'])]
    public function login(UserInterface $user, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        return new JsonResponse([
            'token' => $jwtManager->create($user),
        ]);
    }
    #[Route('/logout', name: 'app_logout')] // Annotation définissant la route '/logout' pour gérer la déconnexion des utilisateurs
    public function logout(): void {} // Méthode vide pour gérer la déconnexion. Symfony gère automatiquement la déconnexion, donc cette méthode ne nécessite pas de code
}