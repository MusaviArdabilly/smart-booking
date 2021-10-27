<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Floor;
use App\Models\Sector;
use App\Models\Desk;
use App\Models\User;
use Illuminate\Support\Carbon;
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
        // return $most_logs;

        $most = new stdClass();
        $most->bookings     = $most_bookings;
        $most->assessments  = $most_assessments;
        $most->logs         = $most_logs;

        return view('synapsis', compact('most'));
    }
}
