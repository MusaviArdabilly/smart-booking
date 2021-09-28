<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

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
        }

        $this->info('Successfully auto checkout');
    }
}
