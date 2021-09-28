<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

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
            })->update(['status' => 'canceled']);

        $this->info('Successfully auto cancel');
    }
}
