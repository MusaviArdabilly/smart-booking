<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Desk;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DeskController extends ApiController
{
    /**
     * A list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $floor_id)
    {
        // rules validator
        $validator = Validator::make($request->all(), [
            'date'          => ['required', 'date_format:Y-m-d'],
            'start_time'    => ['required', 'date_format:H:i'],
            'end_time'      => ['required', 'date_format:H:i', 'after:start_time'],
        ]);
        // response validate
        if ($validator->fails()) {
            return $this->sendInvalid('Validation errors', $validator->errors());
        }

        $start  = Carbon::parse($request->start_time);
        $end    = Carbon::parse($request->end_time);

        $desks = Desk::whereHas('sector', function ($sector) use ($floor_id) {
            $sector->where('floor_id', $floor_id);
        })->get();

        // will renew with booking logic
        foreach ($desks as $desk) {
            // disabled desk
            if ($desk->is_enable == 0) {
                $desk->is_available = 0;
                continue;
            }

            $bookings = $desk->booking->where('date', $request->date);
            $i = 0;
            $booked = [];
            foreach ($bookings as $booking) {
                $start_booking  = Carbon::parse($booking->time->start);
                $end_booking    = Carbon::parse($booking->time->end);

                if (($start_booking >= $start && $start_booking < $end) || ($end_booking >= $start && $end_booking < $end)) {
                    $booked[$i++] = $booking;
                    // $booking->start_time = $booking->time->start;
                    // $booking->end_time = $booking->time->end;
                    // unset($booking->time);
                }
            }
            unset($desk->booking);

            if (count($booked)) {
                $desk->is_available = 0;
                $desk->booked = $booked;
                continue;
            }

            $desk->is_available = 1;
        }

        return $this->sendResponse('Desks listed succesfully', $desks);
    }
}
