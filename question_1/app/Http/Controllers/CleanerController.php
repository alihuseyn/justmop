<?php

namespace App\Http\Controllers;

use App\Models\Cleaner;
use Illuminate\Http\Request;
use App\Http\Resources\Cleaner as CleanerResource;

class CleanerController extends Controller
{
    /**
     * Return list of all cleaners
     *
     * @OA\Get(
     *     path="/cleaners",
     *     summary="Retrieve all cleaners",
     *     description="Retrieve all cleaners",
     *     tags={"Cleaners"},
     *     @OA\Response(
     *         response=200,
     *         description="List of all cleaners",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/CleanerResponse")
     *              )
     *         )
     *     )
     * )
     *
     * @return Response
     */
    public function index()
    {
        return CleanerResource::collection(Cleaner::all());
    }

     /**
     * Create new cleaner
     *
     * @OA\Post(
     *     path="/cleaners",
     *     summary="Create new cleaner",
     *     description="Create new cleaner",
     *     tags={"Cleaners"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Cleaner name identifier",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity"
     *     )
     * )
     *
     * @param Request $request Request Instance
     *
     * @throws \Exception validation exception
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [ 'name' => 'required|string' ]);
        Cleaner::create([ 'name' => $request->name ]);

        return response(null, 201);
    }
}
