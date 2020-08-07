<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Resources\Company as CompanyResource;

class CompanyController extends Controller
{
    /**
     * Return list of all companies
     *
     * @OA\Get(
     *     path="/companies",
     *     summary="Retrieve all companies",
     *     description="Retrieve all companies",
     *     tags={"Companies"},
     *     @OA\Response(
     *         response=200,
     *         description="List of all companies",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/CompanyResponse")
     *              )
     *         )
     *     )
     * )
     *
     * @return Response
     */
    public function index()
    {
        return CompanyResource::collection(Company::all());
    }

    /**
     * Create new company
     *
     * @OA\Post(
     *     path="/companies",
     *     summary="Create new company",
     *     description="Create new company",
     *     tags={"Companies"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Company name identifier",
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
        Company::create([ 'name' => $request->name ]);

        return response(null, 201);
    }
}
