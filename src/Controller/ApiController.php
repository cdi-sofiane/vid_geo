<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Service\ApiMessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api", name="api")
 * @IsGranted("ROLE_USER")
 */
class ApiController extends AbstractController
{
    public $apiMsg;
    public function __construct(ApiMessageService $apiMsg)
    {
        $this->apiMsg = $apiMsg;
    }
    /**
     * @Route("/", name="api")
     */
    public function index(UserRepository $userRepository, SessionInterface $session): Response
    {


        $user = $userRepository->findOneBy(['email' => $session->get('_security.last_username')]);
        return $this->render('api/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{id}", name="user",methods={"GET"})
     *  
     * @OA\Get(
     *  tags={"default"}
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns the citys",
     * 
     *  
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class))
     *          
     *     )
     * )
     */
    public function get_User(Request $request, UserRepository $userRepository): Response
    {
        // dd($request->attributes->get('id'));
        $objCity = $userRepository->findOneBy(['id' => $request->attributes->get('id')]);
        $encoders = [new JsonEncoder()];
        $normalizer = [new DateTimeNormalizer(array('datetime_format' => 'd.m.Y')), new ObjectNormalizer()];
        $serializer = new Serializer($normalizer, $encoders);
        $json = $serializer->serialize($objCity, 'json', [
            /** @var Weather $object */
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);


        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
