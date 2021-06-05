<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/dashboard")
 * @IsGranted("ROLE_USER")
 */

class DashboardController extends AbstractController
{
    protected $requestStack;
    protected $request;

    public function __construct()
    {
        // $this->requestStack = $requestStack;
        
    }
    /**
     * @Route("/", name="dashboard")
     */
    public function index(Request $request): Response
    {
      
        return $this->render('dashboard/view_dashboard.html.twig');
    }
}
