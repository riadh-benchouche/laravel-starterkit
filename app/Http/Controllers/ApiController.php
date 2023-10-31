<?php

namespace App\Http\Controllers;

use App\Traits\Api\ApiResponder;
use OpenApi\Annotations as OA;


/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Urban Canopee API Documentation",
 *      description="L5 Swagger OpenApi description",
 *      @OA\Contact(
 *          email="dev@fstck.co"
 *      ),
 *      @OA\License(
 *          name="Nginx License",
 *          url="https://www.nginx.com/nginx"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Demo API Server"
 * )
 *
 */
class ApiController extends Controller
{
    use ApiResponder;
}
