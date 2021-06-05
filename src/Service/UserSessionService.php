<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSessionService extends AbstractController
{
    private $userRepository;
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }
    public function setUserFromRequest($request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $username = $request->get('username');
        $objUser = new User();
        $objUser->setEmail($email)
            ->setPassword($this->encoder->encodePassword($objUser, $password))
            ->setRoles(['ROLE_USER'])
            ->setApiToken(bin2hex(random_bytes(32)));
        /** @var User $objUser */
        $objUser = $this->userRepository->create($objUser);
        $token = new UsernamePasswordToken($objUser, null, 'main', $objUser->getRoles());
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main', serialize($token));
    }
}
