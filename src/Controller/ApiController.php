<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Entity\Area;
use App\Repository\AreaRepository;
use App\Service\ApiMessageService;
use App\Service\JsonSerialService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("api/")
 * @IsGranted("ROLE_USER")
 */
class ApiController extends AbstractController
{
    public $apiMsg;
    public $jsonSerialService;
    public function __construct(ApiMessageService $apiMsg, JsonSerialService $jsonSerialService)
    {
        $this->apiMsg = $apiMsg;
        $this->JsonSerialService = $jsonSerialService;
    }


    /**
     * @Route("users", name="users",methods={"GET"})
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
     * 
     */
    public function get_users(Request $request, UserRepository $userRepository): Response
    {
        $objCity = $userRepository->findAll();
        $response = $this->JsonSerialService->mySerializer($objCity);

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("user/{id}", name="user",methods={"GET"})
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
        $objCity = $userRepository->findOneBy(['id' => $request->attributes->get('id')]);
        $response = $this->JsonSerialService->mySerializer($objCity);

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }



    /**
     * @Route("area", name="area",methods={"GET"})
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
        $response = $this->JsonSerialService->mySerializer($objArea);


        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("area/{code}", name="one_area",methods={"GET"})
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

        if (!$valueOfArea) {
            return null;
        }
        $area = new Area();
        $area->setCode($valueOfArea[0]->code)
            ->setName($valueOfArea[0]->nom);
        $objArea = $areaRepository->getOrCreate($area);


        $response = $this->JsonSerialService->mySerializer($objArea);

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
