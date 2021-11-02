<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        foreach ($users as $user) {
            if (empty($user->phone)) {
                $user->phone = '-';
            }
            $user->role = ucfirst($user->role);
        }
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
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
        $validate = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'username'  => ['required', 'string', 'max:255', 'unique:users', 'alpha_dash'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'     => ['numeric', 'digits_between:11,13', 'nullable'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
        ]);

        return redirect()->route('user.index')
            ->with('success', 'User created successfully');
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
        // $media = $user->getMedia();
        return view('admin.user.edit')->with(compact('user'));
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

        // if ($request->password) {
        //     $validate = $request->validate([
        //         'password'  => ['required', 'string', 'min:8', 'confirmed'],
        //     ]);
        //     $request->password = Hash::make($request->password);
        // }

        $validate = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'username'  => ['required', 'string', 'max:255', 'alpha_dash'],
            'email'     => ['required', 'string', 'email', 'max:255'],
            'phone'     => ['numeric', 'digits_between:11,13', 'nullable'],
        ]);
        $user->update($request->all());

        return redirect()->route('user.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'Floor deleted successfully');
    }

    // public function notification()
    // {
    //     $users = User::all();

    //     $types = [
    //         'booking_create',
    //         'booking_checkin',
    //         'booking_checkout',
    //         'booking_checkoutAuto',
    //         'booking_checkoutAdmin',
    //         'booking_cancel',
    //         'assessment_create',
    //     ];

    //     foreach ($users as $user) {
    //         foreach ($types as $type) {
    //             // store new booking time
    //             $notification = new Notification();
    //             $notification->type     = $type;
    //             $notification->is_mail  = 1;
    //             $notification->is_push  = 1;
    //             $user->notification()->save($notification);
    //         }
    //     }

    //     return 'success';
    // }
}
