<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('username', '=', auth()->user()->username)->with('notification')->firstOrFail();
        return view('admin.profile.index')->with(compact('user'));
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

            return redirect()->route('profile.index')
                ->with('success', 'Avatar updated successfully');
        }

        // password update
        if ($request->password) {
            $validate = $request->validate([
                'old_password'  => ['required'],
                'password'      => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->save();

                return redirect()->route('profile.index')
                    ->with('success', 'Password updated successfully');
            }

            return redirect()->route('profile.index')
                ->with('error', 'Old Password does not match');
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

            return redirect()->route('profile.index')
                ->with('success', 'Notification updated successfully');
        }


        // rules validator
        if ($user->username != $request->username) {
            $validate = $request->validate([
                'username'  => ['required', 'string', 'max:255', 'unique:users', 'alpha_dash'],
            ]);
        }

        if ($user->email != $request->email) {
            $validate = $request->validate([
                'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
        }

        if ($request->name) {
            $validate = $request->validate([
                'name'      => ['required', 'string', 'max:255'],
                'username'  => ['required', 'string', 'max:255', 'alpha_dash'],
                'email'     => ['required', 'string', 'email', 'max:255'],
                'phone'     => ['numeric', 'digits_between:11,13', 'nullable'],
            ]);
        }

        $user->update($request->all());

        return redirect()->route('profile.index')
            ->with('success', 'Profile updated successfully');
    }
}
