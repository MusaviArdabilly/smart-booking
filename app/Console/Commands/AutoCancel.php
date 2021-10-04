<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

use App\Mail\BookingCancelNotification;
use Illuminate\Support\Facades\Mail;

class AutoCancel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto cancel overtime booked transaction';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now    = Carbon::now()->subMinute('15');
        $booked = Booking::whereDate('date', '<', $now)
            ->where('status', 'booked')
            ->orWhereDate('date', '<=', $now)
            ->where('status', 'booked')
            ->whereHas('time', function ($query) use ($now) {
                $query->whereTime('start', '<', $now);
            })->get();

        foreach ($booked as $booking) {
            $booking->status = 'canceled';
            $booking->save();

            $email      = $booking->user->email;
            $desk       = $booking->desk->sector->floor->name . ' / ' . $booking->desk->sector->name . ' / ' . $booking->desk->name;
            $duration   = $booking->date . ', ' . $booking->time->start . '-' . $booking->time->end;
            $cancel     = $booking->updated_at;
            $maildata = [
                'title'     => 'Your Booking Canceled',
                'id'        => $booking->book_id,
                'desk'      => $desk,
                'duration'  => $duration,
                'cancel'    => $cancel,
            ];
            try {
                Mail::to($email)->send(new BookingCancelNotification($maildata));
            } catch (\Throwable $th) {
                $response_email = ', email';
            }
        }

        $this->info('Successfully auto cancel');
    }
}
