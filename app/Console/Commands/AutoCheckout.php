<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

use App\Jobs\SendQueueMailJob;
use App\Notifications\SendNotification;

class AutoCheckout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:checkout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto checkout overtime checked-in transaction';

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
        $now    = Carbon::now();
        $booked = Booking::whereDate('date', '<', $now)
            ->where('status', 'checked-in')
            ->orWhereDate('date', '<=', $now)
            ->where('status', 'checked-in')
            ->whereHas('time', function ($query) use ($now) {
                $query->whereTime('end', '<=', $now);
            })->get();

        foreach ($booked as $booking) {
            $booking->status = 'checked-out';
            $booking->time()->update(['checkout' => Carbon::now()]);
            $booking->save();

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
                'email'     => $email,
                'markdown'  => 'mails.booking-checkout',
            ];
            try {
                $job = (new SendQueueMailJob($maildata))->delay(now()->addSeconds(2));
                dispatch($job);
            } catch (\Throwable $th) {
                $response_email = ', email';
            }

            $notifdata = [
                'topic' => $booking->user->id,
                'notification' => [
                    "title" => "You Booking has been Auto Checked Out",
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
                return $this->sendResponse('Booking created succesfully without notification', $th);
            }
        }

        $this->info('Successfully auto checkout');
    }
}
