<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Floor;
use App\Models\Sector;
use App\Models\Desk;
use App\Models\User;
use App\Models\Booking;
use App\Models\AssessmentLog;
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

    public function activityMonth()
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
        }

        return response()->json(compact('days', 'bookings', 'assessments'));
    }

    public function logMonth()
    {
        $first_date = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $last_date  = Carbon::now()->lastOfMonth()->endOfDay()->format('Y-m-d');
        $period     = CarbonPeriod::create($first_date, $last_date);

        $hours = [];

        $i = 0;
        while ($i < 24) {
            $hours[] = $i;
            $j = $i + 1;
            $start = Carbon::parse('2021-01-01 ' . $i . ':00')->format('H:i');
            $end = Carbon::parse('2021-01-01 ' . $j . ':00')->format('H:i');

            $count_log = AssessmentLog::whereTime('created_at', '>=', $start)
                ->whereTime('created_at', '<', $end)
                ->whereDate('created_at', '>=', $first_date)
                ->whereDate('created_at', '<=', $last_date)->count();
            $logs[$i] = $count_log;

            $count_booking = Booking::whereDate('date', '>=', $first_date)
                ->whereDate('date', '<=', $last_date)
                ->whereHas('time', function ($query) use ($start, $end) {
                    $query->whereTime('start', '<=', $start)
                        ->whereTime('end', '>=', $end);
                })->count();
            $booking[$i] = $count_booking;

            $count_checkin = Booking::whereDate('date', '>=', $first_date)
                ->whereDate('date', '<=', $last_date)
                ->whereHas('time', function ($query) use ($start, $end) {
                    $query->whereTime('checkin', '>=', $start)
                        ->whereTime('checkin', '<', $end);
                })->count();
            $checkin[$i] = $count_checkin;

            $count_checkout = Booking::whereDate('date', '>=', $first_date)
                ->whereDate('date', '<=', $last_date)
                ->whereHas('time', function ($query) use ($start, $end) {
                    $query->whereTime('checkout', '>=', $start)
                        ->whereTime('checkout', '<', $end);
                })->count();
            $checkout[$i] = $count_checkout;

            $i++;
        }

        return response()->json(compact('hours', 'logs', 'booking', 'checkin', 'checkout'));
    }
}
