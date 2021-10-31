<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Jobs\SendQueueMailJob;
use App\Notifications\SendNotification;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = Booking::orderByRaw("FIELD(status , 'checked-in', 'booked', 'checked-out', 'canceled') ASC")
            ->orderBy('date', 'desc')->get();
        // return $bookings;
        foreach ($bookings as $booking) {
            try {
                $start_time = Carbon::createFromFormat('H:i:s', $booking->time->start)->format('H:i');
                $end_time = Carbon::createFromFormat('H:i:s', $booking->time->end)->format('H:i');
                $booking->time = $start_time . '-' . $end_time;
            } catch (\Throwable $th) {
                $booking->time = '-';
            }
            // $booking->status = ucfirst($booking->status);
            $booking->desk = $booking->desk->sector->floor->name . ' / ' . $booking->desk->sector->name . ' / ' . $booking->desk->name;
        }
        return view('admin.booking.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        //
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
     * Update the status to checked-out in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function checkout(Booking $booking)
    {
        // update booking status
        $booking->status = 'checked-out';
        $booking->save();

        $email      = $booking->user->email;
        $desk       = $booking->desk->sector->floor->name . ' / ' . $booking->desk->sector->name . ' / ' . $booking->desk->name;
        $duration   = $booking->date . ', ' . $booking->time->start . '-' . $booking->time->end;
        $checkin    = $booking->time->checkin;
        $checkout   = $booking->time->checkout;
        $maildata = [
            'title'     => 'You Checked Out by Admin',
            'id'        => $booking->book_id,
            'desk'      => $desk,
            'duration'  => $duration,
            'checkin'   => $checkin,
            'checkout'  => $checkout,
            'email'     => $email,
            'markdown'  => 'mails.booking-checkout',
        ];
        try {
            // Mail::to($email)->send(new BookingCheckOutMail($maildata));
            $job = (new SendQueueMailJob($maildata))->delay(now()->addSeconds(2));
            dispatch($job);
        } catch (\Throwable $th) {
            $response_email = ', email';
        }

        $notifdata = [
            'topic' => $booking->user->id,
            'notification' => [
                "title" => "You Check Out at " . $checkout . " by Admin",
                "body"  => "with ID " . $booking->book_id
            ],
            'data' => [
                "DIRECT_ID" => 2,
                "EXTRA_ID" => $booking->id
            ]
        ];

        try {
            new SendNotification($notifdata);
        } catch (\Throwable $th) {
            return $this->sendResponse('Booking check in succesfully without notification', $th);
        }

        return response()->json(['message' => 'Status updated to Check Out successfully.']);
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
}
