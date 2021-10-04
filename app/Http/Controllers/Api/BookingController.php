<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Mail\BookingCreatedNotification;
use App\Mail\BookingCheckInNotification;
use App\Mail\BookingCheckOutNotification;
use Illuminate\Support\Facades\Mail;

class BookingController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
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

        return $this->sendResponse('Bookings listed succesfully', $bookings);
    }

    public function today($user_id)
    {
        $today = Carbon::today()->toDateString();
        $bookings = Booking::where('user_id', $user_id)->whereDate('date', $today)
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

        return $this->sendResponse('Today Bookings listed succesfully', $bookings);
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
        $desk_start = Booking::where('desk_id', $request->desk_id)->whereDate('date', $request->date)
            ->whereHas('time', function ($query) use ($start, $end) {
                $query->whereTime('start', '>=', $start)
                    ->WhereTime('start', '<=', $end);
            })
            ->with('time')->first();
        if ($desk_start) {
            $start_time = Carbon::parse($desk_start->time->start);
            if ($end == $start_time) {
                // do nothing if it's ended before already booked
            } else {
                return $this->sendInvalid('Desk already booked at start time', $desk_start);
            }
        }

        // booked/used desk on end_time
        $desk_end = Booking::where('desk_id', $request->desk_id)->whereDate('date', $request->date)
            ->whereHas('time', function ($query) use ($start, $end) {
                $query->whereTime('end', '>=', $start)
                    ->WhereTime('end', '<=', $end);
            })
            ->with('time')->first();
        if ($desk_end) {
            $end_time = Carbon::parse($desk_end->time->end);
            if ($start == $end_time) {
                // do nothing if it's started after already booked
            } else {
                return $this->sendInvalid('Desk already booked at end time', $desk_end);
            }
        }

        // user already booked/used a desk on start_time
        $user_start = Booking::where('user_id', $request->user_id)->whereDate('date', $request->date)
            ->whereHas('time', function ($query) use ($start, $end) {
                $query->whereTime('start', '>=', $start)
                    ->WhereTime('start', '<=', $end);
            })
            ->with('time')->first();
        if ($user_start) {
            $start_time = Carbon::parse($user_start->time->start);
            if ($end == $start_time) {
                // do nothing if it's ended before already already
            } else {
                return $this->sendInvalid('You already booked a desk at start time', $user_start);
            }
        }

        // user already booked/used a desk on end_time
        $user_end = Booking::where('user_id', $request->user_id)->whereDate('date', $request->date)
            ->whereHas('time', function ($query) use ($start, $end) {
                $query->whereTime('end', '>=', $start)
                    ->WhereTime('end', '<=', $end);
            })
            ->with('time')->first();
        if ($user_end) {
            $end_time = Carbon::parse($user_end->time->end);
            if ($start == $end_time) {
                // do nothing if it's started after already already
            } else {
                return $this->sendInvalid('You already booked a desk at end time', $user_end);
            }
        }

        // store new booking
        $booking = new Booking();
        $booking->book_id   = 'BK' . Carbon::now()->format('YmdHis');
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

        // $response_email     = "";
        // $response_telegram  = "";
        // $response_whatsapp  = "";
        $email      = $booking->user->email;
        $desk       = $booking->desk->sector->floor->name . ' / ' . $booking->desk->sector->name . ' / ' . $booking->desk->name;
        $duration   = $booking->date . ', ' . $booking->time->start . '-' . $booking->time->end;
        $maildata = [
            'title'     => 'You booked a desk',
            'id'        => $booking->book_id,
            'desk'      => $desk,
            'duration'  => $duration,
        ];
        try {
            Mail::to($email)->send(new BookingCreatedNotification($maildata));
        } catch (\Throwable $th) {
            $response_email = ', email';
            return $this->sendResponse('Booking created succesfully without email', $booking);
        }
        // return $this->sendResponse('Booking created succesfully without' . $response_email . $response_telegram . $response_whatsapp.' notification', $booking);

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
            return $this->sendInvalid('You in the wrong desk', '');
        }

        $today  = Carbon::today();
        $now    = Carbon::now();
        // $today  = Carbon::parse($booking->date)->format('Y-m-d');
        // $now    = Carbon::parse($booking->date . ' ' . $booking->start_time)->subMinute('10')->format('Y-m-d H:i');
        $start  = Carbon::parse($booking->date . ' ' . $booking->time->start)->subMinute('15');
        $end    = Carbon::parse($booking->date . ' ' . $booking->time->start)->addMinute('15');

        // check if too soon / late
        if ($now < $start) {
            return $this->sendInvalid('You need to wait', '');
        } else if ($now > $end) {
            return $this->sendInvalid('You are too late', '');
        }

        // booked/used desk
        $booked = Booking::where('desk_id', $booking->desk_id)->whereDate('date', $today)
            ->where('status', 'checked-in')
            ->whereHas('time', function ($query) use ($start, $end) {
                $query->whereTime('end', '>=', $start)
                    ->WhereTime('end', '<=', $end);
            })
            ->with('time')->first();
        if ($booked) {
            return $this->sendInvalid('Your desk still being used', '');
        }

        // update booking status
        $booking->status = 'checked-in';
        $booking->time()->update(['checkin' => Carbon::now()]);
        $booking->save();
        unset($booking->time);

        // get time detail
        $booking->time;

        $email      = $booking->user->email;
        $desk       = $booking->desk->sector->floor->name . ' / ' . $booking->desk->sector->name . ' / ' . $booking->desk->name;
        $duration   = $booking->date . ', ' . $booking->time->start . '-' . $booking->time->end;
        $checkin    = $booking->time->checkin;
        $maildata = [
            'title'     => 'You Check In',
            'id'        => $booking->book_id,
            'desk'      => $desk,
            'duration'  => $duration,
            'checkin'   => $checkin,
        ];
        try {
            Mail::to($email)->send(new BookingCheckInNotification($maildata));
        } catch (\Throwable $th) {
            $response_email = ', email';
        }

        return $this->sendResponse('You have been check-in', $booking);
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
            return $this->sendInvalid('You in the wrong desk', '');
        }

        // update booking status
        $booking->status = 'checked-out';
        $booking->time()->update(['checkout' => Carbon::now()]);
        $booking->save();

        // get time detail
        $booking->time;

        $email      = $booking->user->email;
        $desk       = $booking->desk->sector->floor->name . ' / ' . $booking->desk->sector->name . ' / ' . $booking->desk->name;
        $duration   = $booking->date . ', ' . $booking->time->start . '-' . $booking->time->end;
        $checkin    = $booking->time->checkin;
        $checkout   = $booking->time->checkout;
        $maildata = [
            'title'     => 'You Check Out',
            'id'        => $booking->book_id,
            'desk'      => $desk,
            'duration'  => $duration,
            'checkin'   => $checkin,
            'checkout'  => $checkout,
        ];
        try {
            Mail::to($email)->send(new BookingCheckOutNotification($maildata));
        } catch (\Throwable $th) {
            $response_email = ', email';
        }

        return $this->sendResponse('You have been check-out', $booking);
    }
}
