<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Helper\FileUploaderHelper;
use App\Service\User\RegistrationService;
use App\Traits\ValidationTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Route("/api/users")
 */
class RegisterController extends AbstractController
{
    use ValidationTrait;

    public function __construct(
        private EntityManagerInterface       $entityManager,
        private UserPasswordEncoderInterface $passwordEncoder,
        private ValidatorInterface           $validator,
        private FileUploaderHelper           $fileUploader,
        private RegistrationService          $userRegistrationService
    )
    {
    }

    /**
     * @Route("/register", name="register_users", methods={"POST"})
     */
    public function registerUser(
        Request $request
    ): JsonResponse
    {
        $userData = $request->request->all();
        $avatar = $request->files->get('avatar');
        $photosData = $request->files->get('photos', []);

        $errors = $this->validateUserData($userData, $photosData);

        if (empty($errors)) {
            $this->userRegistrationService->registerUser($userData, $avatar, $photosData);
            return $this->json(['success' => true, 'message' => 'User registered successfully'], Response::HTTP_OK);
        }

        return $this->json(['success' => false, 'errors' => $errors], Response::HTTP_BAD_REQUEST);
    }
}
