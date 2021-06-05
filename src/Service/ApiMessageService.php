<?php

namespace App\Service;

use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Expr\Cast\Bool_;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Json;

// use Symfony\Component\Validator\Constraints\Json;

class ApiMessageService
{
    public function __construct()
    {
    }

    public function apiMsg($code,  $bool, $res) :array
    {
        $result = ['code' => $code, 'message' => $bool ? 'fonctionne' : 'fonctionne pas', $res];
        
        return $result;
    }
}
