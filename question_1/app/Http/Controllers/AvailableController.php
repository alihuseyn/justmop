<?php

namespace App\Http\Controllers;

use App\Models\Cleaner;
use App\Exceptions\InvalidAvailableDateException;
use Illuminate\Support\Facades\Validator;
use App\Rules\NotFriday;
use Carbon\Carbon;

class AvailableController extends Controller
{
    /**
     * Return all cleaners available time slot for the given date
     *
     * @OA\Schema(
     *     schema="HourSlotResponse",
     *     type="object",
     *     title="HourSlot",
     *     properties={
     *         @OA\Property(property="start", type="string"),
     *         @OA\Property(property="end", type="string")
     *     }
     * )
     * 
     * @OA\Schema(
     *     schema="AvailableResponse",
     *     type="object",
     *     title="Available",
     *     properties={
     *         @OA\Property(property="id", type="integer"),
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="available", type="array", @OA\Items(ref="#/components/schemas/HourSlotResponse"))
     *     }
     * )
     * 
     * @OA\Get(
     *     path="/cleaners/available/{date}",
     *     summary="Return all cleaners available time slot for the given date",
     *     description="Return all cleaners available time slot for the given date",
     *     tags={"Available"},
     *     @OA\Parameter(
     *         name="date",
     *         in="path",
     *         description="Available Date",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of all cleaners' available time slot",
     *         @OA\JsonContent(
     *             @OA\Items(ref="#/components/schemas/AvailableResponse")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid date or less than today",
     *         @OA\JsonContent(ref="#/components/schemas/SimpleErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity"
     *     )
     * )
     *
     * @param string $date Checked time
     *
     * @throws Exception
     *
     * @return Response
     */
    public function index($date)
    {
        $rules = ['date'    => ['date_format:Y-m-d', new NotFriday]];
        $validator = Validator::make(['date' => $date], $rules);

        if ($validator->fails()) {
            return response($validator->errors(), 422);
        }

        $date = Carbon::createFromFormat('Y-m-d', $date);
        if ($date->lt(Carbon::today())) {
            throw new InvalidAvailableDateException;
        }

        $cleaners = Cleaner::all();
        $first = Carbon::createFromFormat('H:i', '08:00');
        $last = Carbon::createFromFormat('H:i', '22:00');

        $result = [];
        foreach ($cleaners as $cleaner) {
            $bookings = $cleaner
                ->bookings
                ->where('date', $date->format('Y-m-d'))
                ->sortBy('start')
                ->all();

            $interval = [];
            $start = $first;

            foreach ($bookings as $booking) {
                if ($start->lt($booking->start)) {
                    array_push($interval, [
                        'start' => $start->format('H:i'),
                        'end' => $booking->start->format('H:i')
                    ]);
                }

                $start = $booking->end;
            }

            if ($start->lt($last)) {
                array_push($interval, [
                    'start' => $start->format('H:i'),
                    'end' => $last->format('H:i')
                ]);
            }

            array_push($result, [
                'id' => $cleaner->id,
                'name' => $cleaner->name,
                'available' => $interval
            ]);
        }

        return response()->json($result);
    }
}
