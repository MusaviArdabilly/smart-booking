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
        $user = User::where('username', '=', auth()->user()->username)->firstOrFail();
        return view('admin.profile.index')->with(compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
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
        // return $request->all();
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
                    ->with('success', 'Password changed');
            }

            return redirect()->route('profile.index')
                ->with('error', 'Old Password does not match');
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
