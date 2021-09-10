<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
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
            return $this->sendInvalid($validator->errors(), 'Validation errors');
        }

        // create new desk
        $booking = Booking::create([
            'book_id'       => 'BK' . Carbon::now()->format('YmdHis'),
            'user_id'       => $request->user_id,
            'desk_id'       => $request->desk_id,
            'date'          => $request->date,
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
        ]);

        $booking = $request->all();

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

        // $today = Carbon::today()->format('Y-m-d');
        // $now = Carbon::now()->format('Y-m-d  H:i');
        $today = Carbon::parse($booking->date)->format('Y-m-d');
        $now = Carbon::parse($booking->date . ' ' . $booking->start_time)->subMinute('15')->format('Y-m-d H:i');
        $before = Carbon::parse($booking->date . ' ' . $booking->start_time)->subMinute('15')->format('Y-m-d H:i');
        $after = Carbon::parse($booking->date . ' ' . $booking->start_time)->addMinute('15')->format('Y-m-d H:i');

        if ($now < $before) {
            return $this->sendInvalid('You need to wait!', '');
        } else if ($now > $after) {
            return $this->sendInvalid('You are too late!', '');
        }

        // booked/used desk
        $booking_before = Booking::where('desk_id', $booking->desk_id)->where('date', $today)
            ->where('status', 'checked-in')
            ->where(function ($query) use ($before, $after) {
                $query->whereBetween('end_time', [$before, $after]);
            })->get();
        if (count($booking_before)) {
            return $this->sendInvalid('Your desk still being used!', '');
        }

        // update booking status
        $booking->status = 'checked-in';
        $booking->save();

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
        $booking->save();

        return $this->sendResponse('You have been check-out!', $booking);
    }
}
