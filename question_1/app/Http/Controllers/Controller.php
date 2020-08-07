<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
        /**
     * @OA\Info(
     *   title="JustMop Question 1 App",
     *   description="Booking Restful service for cleaners and companies",
     *   version="1.0",
     *   @OA\Contact(
     *     email="alihuseyn13@list.ru",
     *     name="Alihuseyn Gulmammadov"
     *   )
     * )
     *
     * @OA\Schema(
     *     schema="SimpleErrorResponse",
     *     type="object",
     *     title="SimpleError",
     *     @OA\Property(property="error", type="string")
     * )
     *
     */
}
