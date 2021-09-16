<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // rules validator
        $validator = Validator::make($request->all(), [
            'user_id'       => ['required'],
            'desk_id'       => ['required'],
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
                return $this->sendInvalid('Desk already booked at start time!', $booked_start);
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
                return $this->sendInvalid('Desk already booked at end time!', $booked_end);
            }
        }

        // store new booking
        $booking = new Booking();
        $booking->book_id        = 'BK' . Carbon::now()->format('YmdHis');
        $booking->user_id   = (int)$request->user_id;
        $booking->desk_id   = (int)$request->desk_id;
        $booking->date      = $request->date;
        $booking->save();

        // store new booking time
        $booking_time = new BookingTime();
        $booking_time->start    = $request->start_time;
        $booking_time->end      = $request->end_time;
        $booking->time()->save($booking_time);

        // get time detail
        $booking->time;

        return $this->sendResponse('Booking created succesfully', $booking);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        // get parent name
        $booking->floor_name    = $booking->desk->sector->floor->name;
        $booking->sector_name   = $booking->desk->sector->name;
        $booking->desk_name     = $booking->desk->name;

        // get sector image
        $sector = $booking->desk->sector;
        $media = $sector->getMedia();
        $url = [];
        try {
            foreach ($sector->media as $item) {
                $url[] = $item->getUrl();
            }
            if (!empty($url)) {
                $booking->media_url = $url;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        unset($booking->desk);

        // get time detail
        $booking->time;

        return $this->sendResponse('', $booking);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        //
    }

    /**
     * Check and update when checkin
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function checkin(Request $request, Booking $booking)
    {
        // desk check
        if ($booking->desk_id != $request->desk_id) {
            return $this->sendInvalid('You in the wrong desk!', '');
        }

        $today  = Carbon::today()->format('Y-m-d');
        $now    = Carbon::now()->format('Y-m-d  H:i');
        // $today  = Carbon::parse($booking->date)->format('Y-m-d');
        // $now    = Carbon::parse($booking->date . ' ' . $booking->start_time)->subMinute('10')->format('Y-m-d H:i');
        $start  = Carbon::parse($booking->date . ' ' . $booking->start_time)->subMinute('15')->format('Y-m-d H:i');
        $end    = Carbon::parse($booking->date . ' ' . $booking->start_time)->addMinute('15')->format('Y-m-d H:i');

        // check if too soon / late
        if ($now < $start) {
            return $this->sendInvalid('You need to wait!', '');
        } else if ($now > $end) {
            return $this->sendInvalid('You are too late!', '');
        }

        // booked/used desk
        $booked = Booking::where('desk_id', $booking->desk_id)->where('date', $today)
            ->where('status', 'checked-in')
            ->whereHas('time', function ($query) use ($start, $end) {
                $query->whereTime('end', '>=', $start)
                    ->WhereTime('end', '<=', $end);
            })
            ->with('time')->first();
        if ($booked) {
            return $this->sendInvalid('Your desk still being used!', '');
        }

        // update booking status
        $booking->status = 'checked-in';
        $booking->time()->update(['checkin' => Carbon::now()]);
        $booking->save();

        // get time detail
        $booking->time;

        return $this->sendResponse('You have been check-in!', $booking);
    }

    /**
     * Check and update when checkin
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request, Booking $booking)
    {
        // desk check
        if ($booking->desk_id != $request->desk_id) {
            return $this->sendInvalid('You in the wrong desk!', '');
        }

        // update booking status
        $booking->status = 'checked-out';
        $booking->time()->update(['checkout' => Carbon::now()]);
        $booking->save();

        // get time detail
        $booking->time;

        return $this->sendResponse('You have been check-out!', $booking);
    }
}
