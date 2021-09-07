<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sector;
use App\Models\Desk;
use Illuminate\Http\Request;

class DeskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($floor_id, $sector_id)
    {
        // $desks = Desk::where('sector_id', $sector_id)->get();
        // return view('admin.desk.index', compact('desks', 'sector_id'));
        // return view('admin.desk.index');
        $sector = Sector::find($sector_id);
        $floor = $sector->floor;
        $media = $sector->getMedia();

        return view('admin.desk.index', compact('sector'));
    }

    /**
     * A list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list($floor_id, $sector_id)
    {
        $desks = Desk::where('sector_id', $sector_id)->get();
        // $desks->count = $this->countDataBasedGroup($desks);

        return response()->json(['data' => $desks, 'floor_id' => $floor_id, 'sector_id' => $sector_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($floor_id, $sector_id)
    {
        $sector = Sector::find($sector_id);
        $floor = $sector->floor;

        return view('admin.desk.create', compact('floor', 'sector'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($floor_id, $sector_id, Request $request)
    {
        // rules validator
        $validate = $request->validate([
            'sector_id'     => ['required'],
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:255'],
        ]);

        // create new desk
        $desk = Desk::create([
            'sector_id'     => $request->sector_id,
            'name'          => $request->name,
            'description'   => $request->description,
        ]);

        // return redirect()->route('desk.index')
        //     ->with('success', 'Desk created successfully.');
        return redirect()->route('floor.sector.desk.index', [$floor_id, $sector_id])
            ->with('success', 'Desk created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Desk  $desk
     * @return \Illuminate\Http\Response
     */
    public function show(Desk $desk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Desk  $desk
     * @return \Illuminate\Http\Response
     */
    public function edit($floor_id, $sector_id, Desk $desk)
    {
        // return view('admin.desk.edit')->with(compact('desk', 'floor_id', 'sector_id'));
        $sector = Sector::find($sector_id);
        $floor = $sector->floor;

        return view('admin.desk.edit', compact('desk', 'floor', 'sector'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Desk  $desk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $floor_id, $sector_id, Desk $desk)
    {
        $validate = $request->validate([
            'name' => ['required'],
        ]);

        $desk->update($request->all());

        return redirect()->route('floor.sector.desk.index', [$floor_id, $sector_id])
            ->with('success', 'Desk updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Desk  $desk
     * @return \Illuminate\Http\Response
     */
    public function destroy($floor_id, $sector_id, Desk $desk)
    {
        $desk->delete();

        return redirect()->route('floor.sector.desk.index', [$floor_id, $sector_id])
            ->with('success', 'Desk deleted successfully.');
    }
}
