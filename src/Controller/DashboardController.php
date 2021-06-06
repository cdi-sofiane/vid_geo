<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/dashboard")
 * @IsGranted("ROLE_USER")
 */

class DashboardController extends AbstractController
{
    protected $requestStack;
    protected $userRepository;
    protected $request;
    protected $security;

    public function __construct(RequestStack $requestStack, UserRepository $userRepository, Security $security)
    {
        $this->requestStack = $requestStack;
        $this->userRepository = $userRepository;
        $this->security = $security;
    }
    /**
     * @Route("/", name="dashboard")
     */
    public function index(Request $request): Response
    {
        $loggedUser =  $this->userRepository->findOneBy(['email' => $this->security->getUser()->getUsername()]);
        if ($loggedUser) {

            $loggedUser->setCurrentPosition('{long:46.356;lat:57.56}');
            $updatedUser = $this->userRepository->updateCurrentPosition($loggedUser);
        }
        return $this->render('dashboard/view_dashboard.html.twig', [
            'user' => $updatedUser
        ]);
    }
}
