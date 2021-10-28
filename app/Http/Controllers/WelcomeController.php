<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Floor;
use App\Models\Sector;
use App\Models\Desk;
use App\Models\User;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use stdClass;

class WelcomeController extends Controller
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

        return view('welcome', compact('count', 'most'));
    }

    public function synapsis()
    {
        $most_bookings = User::withCount('bookingMonth')->orderBy('booking_month_count', 'desc')->withCount('booking')->get();
        $most_assessments = User::withCount('assessmentMonth')->orderBy('assessment_month_count', 'desc')->withCount('assessment')->get();
        $most_logs = User::withCount('assessmentLogMonth')->orderBy('assessment_log_month_count', 'desc')->withCount('assessmentLog')->get();

        $most = new stdClass();
        $most->bookings     = $most_bookings;
        $most->assessments  = $most_assessments;
        $most->logs         = $most_logs;

        return view('synapsis', compact('most'));
    }

    public function perday()
    {
        $first_date = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $last_date  = Carbon::now()->lastOfMonth()->endOfDay()->format('Y-m-d');
        $period     = CarbonPeriod::create($first_date, $last_date);
        // Iterate over the period
        $days = [];
        $bookings = [];
        $assessments = [];
        // $logs = [];
        foreach ($period as $date) {
            $date = $date->format('Y-m-d');
            $days[] = $date;

            $user_bookings = User::withCount([
                'booking',
                'booking as booking_count' => function ($query) use ($date) {
                    $query->whereDate('date', $date);
                }
            ])->get();
            $total_booking = 0;
            foreach ($user_bookings as $booking) {
                $total_booking = $total_booking += $booking->booking_count;
            }
            $bookings[] = $total_booking;

            $user_assessments = User::withCount([
                'assessment',
                'assessment as assessment_count' => function ($query) use ($date) {
                    $query->whereDate('created_at', $date);
                }
            ])->get();
            $total_assessment = 0;
            foreach ($user_assessments as $assessment) {
                $total_assessment = $total_assessment += $assessment->assessment_count;
            }
            $assessments[] = $total_assessment;

            // $user_logs = User::withCount([
            //     'assessmentLog',
            //     'assessmentLog as assessment_log_count' => function ($query) use ($date) {
            //         $query->whereDate('created_at', $date);
            //     }
            // ])->get();
            // $total_log = 0;
            // foreach ($user_logs as $log) {
            //     $total_log = $total_log += $log->assessment_log_count;
            // }
            // $logs[] = $total_log;

            // $most_logs = User::withCount('assessmentLog')->get();
        }

        return response()->json(compact('days', 'bookings', 'assessments'));
    }
}
