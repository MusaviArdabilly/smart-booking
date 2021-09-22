<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assessment;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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

        return $this->sendResponse('', $assessments);
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
            'user_id'       => ['required'],
            'point'         => ['required', 'numeric'],
            'file'          => ['required', 'mimes:pdf'],
        ]);
        // response validate
        if ($validator->fails()) {
            return $this->sendInvalid('Validation errors', $validator->errors());
        }

        // create new assessment
        $assessment = Assessment::create([
            'assess_id'     => 'AS' . Carbon::now()->format('YmdHis'),
            'user_id'       => $request->user_id,
            'point'         => $request->point,
            'expires_at'    => Carbon::tomorrow(),
        ]);

        if ($request->hasFile('file')) {
            $assessment->addMediaFromRequest('file')->toMediaCollection('assessments');
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
}
