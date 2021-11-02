<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

use App\Jobs\SendQueueMailJob;
use App\Notifications\SendNotification;

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
            if ($booking->user->notification[5]->is_mail == 1) {
                $desk       = $booking->desk->sector->floor->name . ' / ' . $booking->desk->sector->name . ' / ' . $booking->desk->name;
                $duration   = $booking->date . ', ' . $booking->time->start . '-' . $booking->time->end;
                $cancel     = $booking->updated_at;
                $maildata = [
                    'title'     => 'Your Booking Canceled',
                    'id'        => $booking->book_id,
                    'desk'      => $desk,
                    'duration'  => $duration,
                    'cancel'    => $cancel,
                    'email'     => $email,
                    'markdown'  => 'mails.booking-cancel',
                ];
                try {
                    $job = (new SendQueueMailJob($maildata))->delay(now()->addSeconds(2));
                    dispatch($job);
                } catch (\Throwable $th) {
                    $response_mail = false;
                }
            }

            if ($booking->user->notification[5]->is_mail == 1) {
                $notifdata = [
                    'topic' => $booking->user->id,
                    'notification' => [
                        "title" => "Your Booking has been Auto Canceled",
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
                    $response_push = false;
                }
            }
        }

        $this->info('Successfully auto cancel');
    }
}
