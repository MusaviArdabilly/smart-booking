<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Desk;
use App\Models\Booking;
use Carbon\Carbon;

class DeskController extends ApiController
{
    /**
     * A list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($floor_id)
    {
        $desks = Desk::whereHas('sector', function ($sector) use ($floor_id) {
            $sector->where('floor_id', $floor_id);
        })->get();

        // will renew with booking logic
        foreach ($desks as $desk) {
            $booking = Booking::where('desk_id', $desk->id)->get();
            if (!empty($booking)) {
                $desk->booking = $booking;
                $desk->is_available = 0;
                continue;
            }
            // $desk->is_available = Arr::random([0, 1]);
            $desk->is_available = 1;
        }

        return $this->sendResponse('', $desks);
    }

    /**
     * A list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function available(Request $request, $floor_id)
    {
        $start = $request->start_time;
        $end = $request->end_time;

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

            // booked/used desk on start_time
            $booked_start = Booking::where('desk_id', $request->desk_id)->where('date', $request->date)
                ->whereHas('time', function ($query) use ($start, $end) {
                    $query->whereTime('start', '>=', $start)
                        ->WhereTime('start', '<=', $end);
                })
                ->with('time')->first();
            if ($booked_start) {
                $start_time = Carbon::parse($booked_start->start_time);
                if ($end == $start_time) {
                    // do nothing if it's ended before already booked
                } else {
                    $desk->booking = $booked_start;
                    $desk->is_available = 0;
                    continue;
                }
            }

            // booked/used desk on end_time
            $booked_end = Booking::where('desk_id', $request->desk_id)->where('date', $request->date)
                ->whereHas('time', function ($query) use ($start, $end) {
                    $query->whereTime('end', '>=', $start)
                        ->WhereTime('end', '<=', $end);
                })
                ->with('time')->first();
            if ($booked_end) {
                $end_time = Carbon::parse($booked_end->time->end);
                if ($start == $end_time) {
                    // do nothing if it's started after already booked
                } else {
                    $desk->booking = $booked_end;
                    $desk->is_available = 0;
                    continue;
                }
            }

            // available desk
            // $desk->is_available = Arr::random([0, 1]);
            $desk->is_available = 1;
        }

        return $this->sendResponse('', $desks);
    }
}
