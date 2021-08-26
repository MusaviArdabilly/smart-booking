<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sector;
use Illuminate\Http\Request;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($floor_id)
    {
        // $sectors = Sector::where('floor_id', $floor_id)->get();
        // return view('admin.sector.index', compact('sectors', 'floor_id'));
        // return compact('floor_id');
        return view('admin.sector.index', compact('floor_id'));
    }

    /**
     * A list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list($floor_id)
    {
        $sectors = Sector::where('floor_id', $floor_id)->get();
        // $sectors->count = $this->countDataBasedGroup($sectors);

        return response()->json(['data' => $sectors, 'floor_id' => $floor_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sector.create');
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
            'floor_id'      => ['required'],
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['string', 'max:255'],
        ]);

        // create new sector
        $sector = Sector::create([
            'floor_id'      => $request->floor_id,
            'name'          => $request->name,
            'description'   => $request->description,
        ]);

        return redirect()->route('sector.index')
            ->with('success', 'Sector created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function show(Sector $sector)
    {
        return redirect()->route('desk.index')
            ->with(compact('sector'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function edit($floor_id, Sector $sector)
    {
        return view('admin.sector.edit')->with(compact('sector', 'floor_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $floor_id, Sector $sector)
    {
        $validate = $request->validate([
            'name' => ['required'],
        ]);

        $sector->update($request->all());
        return redirect()->route('floor.sector.index', $floor_id)
            ->with('success', 'Sector updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function destroy($floor_id, Sector $sector)
    {
        $sector->delete();

        return redirect()->route('floor.sector.index', $floor_id)
            ->with('success', 'Sector updated successfully.');
    }
}
