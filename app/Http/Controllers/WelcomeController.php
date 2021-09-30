<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Floor;
use App\Models\Sector;
use App\Models\Desk;
use App\Models\User;
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
}
