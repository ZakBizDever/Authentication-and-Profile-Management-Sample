<?php

namespace App\Controller\User;

use App\Service\User\AuthenticationService;
use App\Traits\ValidationTrait;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/users")
 */
class LoginController extends AbstractController
{
    use ValidationTrait;

    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private AuthenticationService    $authenticationService,
        private ValidatorInterface       $validator
    )
    {
    }

    /**
     * @Route("/login", name="login_user", methods={"POST"})
     */
    public function loginUser(
        Request $request,
    ): JsonResponse
    {
        $data = $request->request->all();
        $violations = $this->validateAuthenticationInput($data);
        if (count($violations) > 0) {
            return $this->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $violations
            ],
                Response::HTTP_BAD_REQUEST);
        }

        $user = $this->authenticationService->authenticateUser($data['email'], $data['password']);

        if ($user === null) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => 'Invalid username or password'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $token = $this->jwtManager->create($user);

        return new JsonResponse(
            [
                "status" => "success",
                "token" => $token
            ],
            Response::HTTP_OK);
    }
}
