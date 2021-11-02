<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends ApiController
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $media = $user->getMedia();
        try {
            if ($user->media[0]->hasGeneratedConversion('thumb')) {
                $user->media_url = $user->media[0]->getUrl('thumb');
            } else {
                $user->media_url = $user->media[0]->getUrl();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        unset($user->media);
        return $this->sendResponse('Profile showed succesfully', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // avatar update
        if ($request->hasFile('file')) {
            $media = $user->getMedia();
            try {
                // delete old media
                $user->media[0]->delete();
            } catch (\Throwable $th) {
                //
            }
            // create new media
            $user->addMediaFromRequest('file')->usingFileName($request->file('file')->hashName())->toMediaCollection('avatars');

            return $this->sendResponse('Avatar updated succesfully', $user);
        }

        // password update
        if ($request->password) {
            // rules validator
            $validator = Validator::make($request->all(), [
                'old_password'  => ['required'],
                'password'      => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            // response validate
            if ($validator->fails()) {
                return $this->sendInvalid('Validation errors', $validator->errors());
            }

            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->save();

                return $this->sendResponse('Password updated succesfully', $user);
            }

            return $this->sendInvalid('Old Password does not match', '');
        }

        // notification update
        if ($request->is_mail && $request->is_push) {
            $user = User::with('notification')->findOrFail($user->id);

            $is_mail = $request->is_mail;
            $is_push = $request->is_push;
            $i = 0;

            foreach ($user->notification as $notification) {
                $notification->is_mail  = $is_mail[$i];
                $notification->is_push  = $is_push[$i++];
                $user->notification()->save($notification);
            }

            unset($user->notification);

            return $this->sendResponse('Notification updated succesfully', $user);
        }

        // rules validator
        if ($user->username != $request->username) {
            // rules validator
            $validator = Validator::make($request->all(), [
                'username'  => ['required', 'string', 'max:255', 'unique:users', 'alpha_dash'],
            ]);
            // response validate
            if ($validator->fails()) {
                return $this->sendInvalid('Validation errors', $validator->errors());
            }
        }

        if ($user->email != $request->email) {
            // rules validator
            $validator = Validator::make($request->all(), [
                'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
            // response validate
            if ($validator->fails()) {
                return $this->sendInvalid('Validation errors', $validator->errors());
            }
        }

        if ($request->name) {
            // rules validator
            $validator = Validator::make($request->all(), [
                'name'      => ['required', 'string', 'max:255'],
                'username'  => ['required', 'string', 'max:255', 'alpha_dash'],
                'email'     => ['required', 'string', 'email', 'max:255'],
                'phone'     => ['numeric', 'digits_between:11,13', 'nullable'],
            ]);
            // response validate
            if ($validator->fails()) {
                return $this->sendInvalid('Validation errors', $validator->errors());
            }
        }

        $user->update($request->all());

        return $this->sendResponse('Profile updated succesfully', $user);
    }
}
