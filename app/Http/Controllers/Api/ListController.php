<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Sector;
use App\Models\Desk;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class ListController extends ApiController
{
    /**
     * A list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function floor()
    {
        $floors = Floor::all();
        // $floors->count = $this->countDataFloor($floors);

        foreach ($floors as $floor) {
            $media = $floor->getMedia();
            try {
                $floor->media_url = $floor->media[0]->getUrl();
            } catch (\Throwable $th) {
                //throw $th;
            }
            unset($floor->media);
        }

        return $this->sendResponse('', $floors);
    }

    /**
     * A list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sector($floor_id)
    {
        $sectors = Sector::where('floor_id', $floor_id)->get();

        foreach ($sectors as $sector) {
            $media = $sector->getMedia();
            $url = [];
            try {
                foreach ($sector->media as $item) {
                    $url[] = $item->getUrl();
                }
                if (!empty($url)) {
                    $sector->media_url = $url;
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
            unset($sector->media);
        }

        return $this->sendResponse('', $sectors);
    }

    /**
     * A list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function desk($floor_id)
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
    public function deskAvailable(Request $request, $floor_id)
    {
        $date = $request->date;
        $start_time = $request->start_time;
        $end_time = $request->end_time;

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

            // booked/used desk
            $booking = Booking::where('desk_id', $desk->id)->where('date', $date)
                ->whereBetween('status', ['booked', 'checked-in'])
                ->where(function ($query) use ($start_time, $end_time) {
                    $query->whereBetween('start_time', [$start_time, $end_time])
                        ->orWhereBetween('end_time', [$start_time, $end_time]);
                })->get();
            if (count($booking)) {
                $desk->booking = $booking;
                $desk->is_available = 0;
                continue;
            }

            // available desk
            // $desk->is_available = Arr::random([0, 1]);
            $desk->is_available = 1;
        }

        return $this->sendResponse('', $desks);
    }

    public function booking($user_id)
    {
        $bookings = Booking::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();

        // add some property
        foreach ($bookings as $booking) {
            // get parent name
            $booking->floor_name    = $booking->desk->sector->floor->name;
            $booking->sector_name   = $booking->desk->sector->name;
            $booking->desk_name     = $booking->desk->name;
            unset($booking->desk);

            // get time detail
            $booking->time;
        }

        return $this->sendResponse('', $bookings);
    }

    public function bookingToday($user_id)
    {
        $today = Carbon::today()->toDateString();
        $bookings = Booking::where('user_id', $user_id)->where('date', $today)
            ->orderByRaw("FIELD(status , 'checked-in', 'booked', 'checked-out', 'cancelled') ASC")
            ->whereHas('time', function ($query) {
                $query->orderBy('start', 'asc');
            })->get();

        // add some property
        foreach ($bookings as $booking) {
            // get parent name
            $booking->floor_name    = $booking->desk->sector->floor->name;
            $booking->sector_name   = $booking->desk->sector->name;
            $booking->desk_name     = $booking->desk->name;
            unset($booking->desk);

            // get time detail
            $booking->time;
        }

        return $this->sendResponse('', $bookings);
    }
}
