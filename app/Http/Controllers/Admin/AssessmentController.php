<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assessments = Assessment::orderBy('created_at', 'desc')->get();
        // orderByRaw("FIELD(status , 'checked-in', 'booked', 'checked-out', 'cancelled') ASC")
        //     ->orderBy('date', 'desc')->get();
        // return $assessments;
        foreach ($assessments as $assessment) {
            $media = $assessment->getMedia();

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

        return view('admin.assessment.index', compact('assessments'));
    }
}
