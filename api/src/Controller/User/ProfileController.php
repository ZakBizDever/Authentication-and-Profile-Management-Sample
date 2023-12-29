<?php

namespace App\Controller\User;


use App\Service\User\ProfileDetailsService;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/users")
 */
class ProfileController extends AbstractController
{
    public function __construct(
        private ProfileDetailsService $userProfileService,
        private SerializerInterface   $serializer
    )
    {
    }

    /**
     * @Route("/me", name="get_user_me", methods={"GET"})
     */
    public function userProfile(
        Request                       $request,
        AuthorizationCheckerInterface $authorizationChecker
    ): JsonResponse
    {
        if (!$authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new JsonResponse(["status" => "error", "message" => "User is not authenticated"],
                Response::HTTP_UNAUTHORIZED
            );
        }

        /** @var JWTUser $user * */
        $user = $this->getUser();

        $responseData = $this->userProfileService->getUserProfileData($user);

        return new JsonResponse([
            $responseData
        ]);
    }
}
