<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\UserSessionService;

class SecurityController extends AbstractController
{

    protected $userRepository;

    function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/", name="app_")
     */
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request): Response
    {
        return $this->render('security/view_register.html.twig');
    }


    /**
     * @Route("/add", name="app_add")
     */
    public function add(Request $request, UserSessionService $userSessionService): RedirectResponse
    {
        $user = $this->userRepository->findOneBy(['email' => $request->request->get('email')]);
        if ($user) {
            return  $this->redirectToRoute('app_register');
        }
        $userSessionService->setUserFromRequest($request->request);



        return  $this->redirectToRoute('dashboard');
    }
}
