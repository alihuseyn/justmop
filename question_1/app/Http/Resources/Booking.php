<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

/**
 * @OA\Schema(
 *     schema="BookingResponse",
 *     type="object",
 *     title="Booking",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="hour", ref="#/components/schemas/HourSlotResponse"),
 *         @OA\Property(property="date", type="string"),
 *         @OA\Property(property="company", ref="#/components/schemas/CompanyResponse")
 *     }
 * )
 */
class Booking extends JsonResource
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
            'hour' => [
                'start' => $this->start->format('H:i'), 
                'end' => $this->end->format('H:i')
            ],
            'date' => $this->date,
            'company' => new Company($this->company)
        ];
    }
}
