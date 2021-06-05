<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @Route("/dashboard")
 * @IsGranted("ROLE_USER")
 */

class DashboardController extends AbstractController
{
    protected $requestStack;
    protected $userRepository;
    protected $request;

    public function __construct(RequestStack $requestStack, UserRepository $userRepository)
    {
        $this->requestStack = $requestStack;
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/", name="dashboard")
     */
    public function index(Request $request): Response
    {
        // dd( $this->requestStack);
        $loggedUser =  $this->userRepository->findOneBy(['email' => $this->get('session')->get('_security.last_username')]);
        
        if ($loggedUser) {

            $loggedUser->setCurrentPosition('{long:46.356;lat:57.56}');
            $updatedUser = $this->userRepository->updateCurrentPosition($loggedUser);
          
        }
        //  $listUsers =$this->userRepository->findAll();
        return $this->render('dashboard/view_dashboard.html.twig', ['user' =>$loggedUser]);
    }
}
