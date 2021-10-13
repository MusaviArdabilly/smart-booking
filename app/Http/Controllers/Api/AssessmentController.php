<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\AssessmentLog;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Mail\AssessmentCreatedNotification;
use Illuminate\Support\Facades\Mail;

class AssessmentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        $assessments = Assessment::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();

        // add some property
        foreach ($assessments as $assessment) {
            $media = $assessment->getMedia();
            try {
                $assessment->media_url = $assessment->media[0]->getUrl();
            } catch (\Throwable $th) {
                //throw $th;
            }
            unset($assessment->media);
        }

        return $this->sendResponse('Assessments listed succesfully', $assessments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // rules validator
        $validator = Validator::make($request->all(), [
            'assess_id'     => ['required'],
            'user_id'       => ['required'],
            'point'         => ['required', 'numeric'],
            'file'          => ['required', 'mimes:pdf'],
        ]);
        // response validate
        if ($validator->fails()) {
            return $this->sendInvalid('Validation errors', $validator->errors());
        }

        // check if user already stored one
        $already = Assessment::where('user_id', $request->user_id)->whereDate('created_at', Carbon::today())->first();
        if ($already) {
            return $this->sendInvalid('Assessment already stored today', $already);
        }

        // create new assessment
        $assessment = Assessment::create([
            // 'assess_id'     => 'AS' . Carbon::now()->format('YmdHis'),
            'assess_id'     => $request->assess_id,
            'user_id'       => $request->user_id,
            'point'         => $request->point,
            'expires_at'    => Carbon::today()->endOfDay(),
        ]);

        if ($request->hasFile('file')) {
            $assessment->addMediaFromRequest('file')->usingFileName($request->file('file')->hashName())->toMediaCollection('assessments');
        }

        $email      = $assessment->user->email;
        $created_at = Carbon::parse($assessment->created_at)->format('Y-m-d H:i:s');
        $expires_at = Carbon::parse($assessment->expires_at)->format('Y-m-d H:i:s');
        $maildata = [
            'title'         => 'You created a new Assessment',
            'id'            => $assessment->assess_id,
            'point'         => $assessment->point,
            'created_at'    => $created_at,
            'expires_at'    => $expires_at,
        ];

        try {
            Mail::to($email)->send(new AssessmentCreatedNotification($maildata));
        } catch (\Throwable $th) {
            $response_email = ', email';
            return $this->sendResponse('Assessment created succesfully without email', $th);
        }

        return $this->sendResponse('Assessment created succesfully', $assessment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Assessment  $assessment
     * @return \Illuminate\Http\Response
     */
    public function show(Assessment $assessment)
    {
        $media = $assessment->getMedia();
        try {
            $assessment->media_url = $assessment->media[0]->getUrl();
        } catch (\Throwable $th) {
            //throw $th;
        }
        unset($assessment->media);

        return $this->sendResponse('', $assessment);
    }

    /**
     * Verif the specified resource.
     *
     * @param  \App\Models\Assessment  $assessment
     * @return \Illuminate\Http\Response
     */
    public function verif(Assessment $assessment)
    {
        // validate if expired
        if (Carbon::today()->endOfDay() > $assessment->expires_at) {
            return $this->sendInvalid('Assessment already Expired');
        }

        // create new assessment log
        $assessment = AssessmentLog::create([
            'assessment_id'     => $assessment->id,
        ]);

        return $this->sendResponse('Verification complete', $assessment);
    }
}
