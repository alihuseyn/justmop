<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Cleaner;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Resources\Booking as BookingResource;
use App\Rules\NotFriday;
use App\Rules\BetweenWorkHours;
use Carbon\Carbon;
use App\Exceptions\InvalidCleaningIntervalException;
use App\Exceptions\AlreadyExistsIntervalException;
use App\Exceptions\InvalidAvailableDateException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Traits\HelperValidatorTrait;

class BookingController extends Controller
{
    use HelperValidatorTrait;

    /**
     * Retrieve cleaner bookings information
     *
     * @OA\Get(
     *     path="/cleaners/{id}/bookings",
     *     summary="Retrieve cleaner bookings information",
     *     description="Retrieve cleaner bookings information",
     *     tags={"Booking"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Cleaner ID",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Created",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/BookingResponse")
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     *
     * @param int $id Cleaner ID
     *
     * @throws \Exception validation exception
     *
     * @return Response
     */
    public function index($id)
    {
        $cleaner = Cleaner::find($id);

        if (empty($cleaner)) {
            throw new NotFoundHttpException;
        }

        $today = Carbon::today();
        $bookings = $cleaner
            ->bookings
            ->where('date', '>=', $today->format('Y-m-d'))
            ->all();

        return BookingResource::collection($bookings);
    }

    /**
     * Create new booking for cleaners
     *
     * @OA\Post(
     *     path="/cleaners/{id}/bookings",
     *     summary="Create new booking for cleaners",
     *     description="Create new booking for cleaners",
     *     tags={"Booking"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Cleaner ID",
     *         required=true
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Booking information detail",
     *         @OA\JsonContent(
     *             @OA\Property(property="company", type="integer"),
     *             @OA\Property(property="start", type="string"),
     *             @OA\Property(property="end", type="string"),
     *             @OA\Property(property="date", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(ref="#/components/schemas/SimpleErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     *
     * @param Request $request Request instance
     * @param int     $id      Cleaner ID
     *
     * @throws \Exception validation exception
     *
     * @return Response
     */
    public function create(Request $request, $id)
    {
        $this->validate($request, [
            'company' => 'required|numeric|exists:companies,id',
            'start'   => ['required', 'date_format:H:i', new BetweenWorkHours],
            'end'     => ['required', 'date_format:H:i', new BetweenWorkHours],
            'date'    => ['required', 'date_format:Y-m-d', new NotFriday]
        ]);

        if (!$this->isValidInterval($request->start, $request->end)) {
            throw new InvalidCleaningIntervalException;
        }

        if (!Carbon::createFromFormat('Y-m-d', $request->date)->gte(Carbon::today())) {
            throw new InvalidAvailableDateException;
        }

        $cleaner = Cleaner::find($id);
        if (empty($cleaner)) {
            throw new NotFoundHttpException;
        }

        $bookings = $cleaner->bookings->where('date', $request->date)->sortBy('start');
        if (!$this->isAvailableIntervalExists($bookings, $request->start, $request->end)) {
            throw new AlreadyExistsIntervalException;
        }

        Booking::forceCreate([
            'start'      => $request->start,
            'end'        => $request->end,
            'date'       => $request->date,
            'cleaner_id' => $id,
            'company_id' => $request->company
        ]);

        return response(null, 201);
    }

    /**
     * Update available booking information
     *
     * @OA\Patch(
     *     path="/cleaners/{cleaner_id}/bookings/{booking_id}",
     *     summary="Update available booking information",
     *     description="Update available booking information",
     *     tags={"Booking"},
     *     @OA\Parameter(
     *         name="cleaner_id",
     *         in="path",
     *         description="Cleaner ID",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="booking_id",
     *         in="path",
     *         description="Booking ID",
     *         required=true
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         description="Booking information detail",
     *         @OA\JsonContent(
     *             @OA\Property(property="start", type="string"),
     *             @OA\Property(property="end", type="string"),
     *             @OA\Property(property="date", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Accepted",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(ref="#/components/schemas/SimpleErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     *
     * @param Request $request     Request instance
     * @param int     $cleaner_id  Cleaner ID
     * @param int     $booking_id  Booking ID
     *
     * @throws \Exception validation exception
     *
     * @return Response
     */
    public function update(Request $request, $cleaner_id, $booking_id)
    {
        $this->validate($request, [
            'start'   => ['date_format:H:i', new BetweenWorkHours],
            'end'     => ['date_format:H:i', new BetweenWorkHours],
            'date'    => ['date_format:Y-m-d', new NotFriday]
        ]);

        $booking = Booking::find($booking_id);
        $cleaner = Cleaner::find($cleaner_id);

        if (empty($cleaner) || empty($booking)) {
            throw new NotFoundHttpException;
        }

        $start = $request->has('start') ? $request->start : $booking->start->format('H:i');
        $end = $request->has('end') ? $request->end : $booking->end->format('H:i');
        $date = $request->has('date') ? $request->date : $booking->date;

        if (!$this->isValidInterval($start, $end)) {
            throw new InvalidCleaningIntervalException;
        }

        if (!Carbon::createFromFormat('Y-m-d', $date)->gte(Carbon::today())) {
            throw new InvalidAvailableDateException;
        }

        // Exclude current booking
        $bookings = $cleaner
            ->bookings
            ->where('date', $date)
            ->where('id', '!=', $booking_id)
            ->sortBy('start');

        if (!$this->isAvailableIntervalExists($bookings, $start, $end)) {
            throw new AlreadyExistsIntervalException;
        }

        $booking->date = $date;
        $booking->start = $start;
        $booking->end = $end;
        $booking->save();

        return response(null, 202);
    }

    /**
     * Delete available booking
     *
     * @OA\Delete(
     *     path="/cleaners/{cleaner_id}/bookings/{booking_id}",
     *     summary="Delete available booking",
     *     description="Delete available booking",
     *     tags={"Booking"},
     *     @OA\Parameter(
     *         name="cleaner_id",
     *         in="path",
     *         description="Cleaner ID",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="booking_id",
     *         in="path",
     *         description="Booking ID",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Accepted",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     *
     * @param Request $request     Request instance
     * @param int     $cleaner_id  Cleaner ID
     * @param int     $booking_id  Booking ID
     *
     * @throws \Exception validation exception
     *
     * @return Response
     */
    public function destroy($cleaner_id, $booking_id)
    {
        $booking = Booking::where('cleaner_id', $cleaner_id)->where('id', $booking_id)->first();

        if (empty($booking)) {
            throw new NotFoundHttpException;
        }

        $booking->delete();

        return response(null, 202);
    }
}
