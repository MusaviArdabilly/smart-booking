<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Floor;
use App\Models\Sector;
use App\Models\Desk;
use App\Models\User;
use App\Models\Booking;
use App\Models\Assessment;
use Carbon\Carbon;
use stdClass;

class DashboardController extends Controller
{
    public function index()
    {
        $count = new stdClass();
        $count->floor  = Floor::count();
        $count->sector = Sector::count();
        $count->desk   = Desk::count();
        $count->user   = User::count();

        $most_desks = Desk::withCount('booking')->orderBy('booking_count', 'desc')->take(3)->get();;
        foreach ($most_desks as $desk) {
            $desk->name = $desk->sector->floor->name . ' / ' . $desk->sector->name . ' / ' . $desk->name;
        }
        $most_users = User::withCount('booking')->orderBy('booking_count', 'desc')->take(3)->get();;
        $most = new stdClass();
        $most->desks  = $most_desks;
        $most->users  = $most_users;

        $count->booking_today   = Booking::whereDate('date', today())->count();
        $count->booking_all     = Booking::count();
        $count->assess_today    = Assessment::whereDate('created_at', today())->count();
        $count->assess_all      = Assessment::count();

        $bookings = Booking::orderBy('id', 'desc')->take(5)->get();
        foreach ($bookings as $booking) {
            $split = str_split($booking->book_id, 8);
            $booking->book_id_1 = $split[0];
            $booking->book_id_2 = $split[1];
            try {
                $start_time = Carbon::createFromFormat('H:i:s', $booking->time->start)->format('H:i');
                $end_time = Carbon::createFromFormat('H:i:s', $booking->time->end)->format('H:i');
                $booking->time = $start_time . '-' . $end_time;
            } catch (\Throwable $th) {
                $booking->time = '-';
            }
            $booking->desk = $booking->desk->sector->floor->name . ' / ' . $booking->desk->sector->name . ' / ' . $booking->desk->name;
        }

        $assessments = Assessment::orderBy('id', 'desc')->take(5)->get();
        foreach ($assessments as $assessment) {
            $media = $assessment->getMedia();

            $split = str_split($assessment->assess_id, 8);
            $assessment->assess_id_1 = $split[0];
            $assessment->assess_id_2 = $split[1];

            $expired = Carbon::now()->diffInHours($assessment->expires_at, false);
            if ($expired < 0) {
                $assessment->expires_at = 'Expired';
                // continue;
            } elseif ($expired <= 1) {
                $expired = Carbon::now()->diffInMinutes($assessment->expires_at, false);
                if ($expired == 1) {
                    $assessment->expires_at = $expired . ' minute';
                    continue;
                }
                $assessment->expires_at = $expired . ' minutes';
            } else {
                $assessment->expires_at = $expired . ' hours';
            }
        }

        return view('admin.dashboard', compact('count', 'bookings', 'assessments', 'most'));
    }
}
