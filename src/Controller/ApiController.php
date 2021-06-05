<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Entity\Area;
use App\Entity\ApiCall;
use App\Repository\AreaRepository;
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
use Swagger\Annotations as SWG;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**
 * 
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
     * @Route("/api/users", name="users",methods={"GET"})
     *  
     * @OA\Get(
     *  tags={"Users"}
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns the users",
     * 
     *  
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class))
     *          
     *     )
     * )
     */
    public function get_users(Request $request, UserRepository $userRepository): Response
    {
        // dd($request->attributes->get('id'));
        $objCity = $userRepository->findAll();
        $encoders = [new JsonEncoder()];
        $normalizer = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizer, $encoders);
        $json = $serializer->serialize($objCity, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);


        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/user/{id}", name="user",methods={"GET"})
     *  
     * @OA\Get(
     *  tags={"Users"}
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
    public function get_user(Request $request, UserRepository $userRepository): Response
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



    /**
     * @Route("api/area", name="area",methods={"GET"})
     *  
     * @OA\Get(
     *  tags={"Area"}
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns the Area",
     * 
     *  
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Area::class))
     *          
     *     )
     * 
     * )
     * @OA\Parameter(name="code",in="query",description="code")
     * @OA\Parameter(name="long",in="query",description="longitide")
     * @OA\Parameter(name="lat",in="query",description="latitude")
     * @OA\Parameter(name="adresse",in="query",description="adresse")
     * )
     */

    public function get_gouv_geo(Request $request, AreaRepository $areaRepository)
    {
        $code = $request->query->get('code');
        if ($code) {

            $valueOfArea = json_decode(file_get_contents("https://geo.api.gouv.fr/communes?code=" . $code));
   
            $message = 'pas de resultats';
            if (!$valueOfArea) {
                $area = new Area();
                $area->setCode($valueOfArea[0]->code)
                     ->setName($valueOfArea[0]->paris);
            }
        }

        $objArea = $areaRepository->findAll();
        $encoders = [new JsonEncoder()];
        $normalizer = [new DateTimeNormalizer(array('datetime_format' => 'd.m.Y')), new ObjectNormalizer()];
        $serializer = new Serializer($normalizer, $encoders);
        $json = $serializer->serialize($objArea, 'json', [
            /** @var Weather $object */
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);


        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("api/area/{code}", name="one_area",methods={"GET"})
     *  
     * @OA\Get(
     *  tags={"Area"}
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="retourne une area par code",
     * 
     *  
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Area::class))
     *          
     *     )
     * 
     * )
     *
     * )
     */

    public function get_one_gouv_geo(Request $request, AreaRepository $areaRepository)
    {
        $code = $request->attributes->get('code');
        $valueOfArea = json_decode(file_get_contents("https://geo.api.gouv.fr/communes?code=" . $code));
        // dd($valueOfArea);
        if (!$valueOfArea) {
            return null;
        }
        $area = new Area();
        $area->setCode($valueOfArea[0]->code)
            // ->setAdresse()
            // ->setLongitude()
            // ->setLatitude()
            ->setName($valueOfArea[0]->nom);
        $objArea = $areaRepository->getOrCreate($area);
        
        $encoders = [new JsonEncoder()];
        $normalizer = [new DateTimeNormalizer(array('datetime_format' => 'd.m.Y')), new ObjectNormalizer()];
        $serializer = new Serializer($normalizer, $encoders);
        $json = $serializer->serialize($objArea, 'json', [
            /** @var Weather $object */
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        // dd($objArea);
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
