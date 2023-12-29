<?php

namespace App\Service\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthenticationService
{
    public function __construct(
        private EntityManagerInterface       $entityManager,
        private UserPasswordEncoderInterface $passwordEncoder
    )
    {
    }

    /**
     * Validate user authentication credentials against real credentials
     *
     * @param string $email
     * @param string $password
     * @return User|null
     */
    public function authenticateUser(string $email, string $password): ?User
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user && $this->passwordEncoder->isPasswordValid($user, $password)) {
            return $user;
        }

        return null;
    }
}
