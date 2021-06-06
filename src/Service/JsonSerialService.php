<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

// use Symfony\Component\Validator\Constraints\Json;

class JsonSerialService
{
    public function __construct()
    {
    }

    public static function mySerializer($obj) :Response
    {
        $encoders = [new JsonEncoder()];
        $normalizer = [new DateTimeNormalizer(array('datetime_format' => 'd.m.Y')), new ObjectNormalizer()];
        $serializer = new Serializer($normalizer, $encoders);
        $json = $serializer->serialize($obj, 'json', [
            /** @var Weather $object */
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        // dd($objArea);
        $response = new Response($json);
       return  $response;
    }
}
