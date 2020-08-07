<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CompanyResponse",
 *     type="object",
 *     title="Company",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string")
 *     }
 * )
 */
class Company extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request Request instance
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
