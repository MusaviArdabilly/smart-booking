<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class FloorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $floors = Floor::all();
        // return view('admin.floor.index')->with(compact('floors'));
        return view('admin.floor.index');
    }

    /**
     * A list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $floors = Floor::all();
        $floors->count = $this->countDataFloor($floors);

        return response()->json(['data' => $floors]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.floor.create');
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
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:255'],
        ]);

        // create new floor
        $floor = Floor::create([
            'name'          => $request->name,
            'description'   => $request->description,
        ]);

        if ($request->hasFile('map')) {
            $floor->addMediaFromRequest('map')->usingFileName($request->file('map')->hashName())->toMediaCollection('maps');
        }

        return redirect()->route('floor.index')
            ->with('success', 'Floor created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Floor  $floor
     * @return \Illuminate\Http\Response
     */
    public function show(Floor $floor)
    {
        // return view('admin.floor.show')->with(compact('floor'));
        // return redirect()->route('sector.index')
        //     ->with(compact('floor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Floor  $floor
     * @return \Illuminate\Http\Response
     */
    public function edit(Floor $floor)
    {
        $media = $floor->getMedia();

        return view('admin.floor.edit')->with(compact('floor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Floor  $floor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Floor $floor)
    {
        $validate = $request->validate([
            'name' => ['required'],
        ]);
        $floor->update($request->all());

        $media = $floor->getMedia();
        if ($request->hasFile('map')) {
            try {
                // delete old media
                $floor->media[0]->delete();
            } catch (\Throwable $th) {
                //
            }
            // create new media
            $floor->addMediaFromRequest('map')->usingFileName($request->file('map')->hashName())->toMediaCollection('maps');
        }

        return redirect()->route('floor.index')
            ->with('success', 'Floor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Floor  $floor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Floor $floor)
    {
        $floor->delete();

        return redirect()->route('floor.index')
            ->with('success', 'Floor deleted successfully.');
    }

    /**
     * @todo count sectors and desks based floor id
     * @param Array $floors
     */
    private function countDataFloor($floors)
    {
        //count sectors and desks based floor id
        foreach ($floors as $floor) {
            //count sector
            $floor->number_of_sectors = $floor->sectors()->count();
            //condition statement
            if ($floor->number_of_sectors == 0) {
                $floor->number_of_desks = 0;
            }
            //count desks
            foreach ($floor->sectors()->get() as $sector) {
                $floor->number_of_desks += $sector->desks()->count();
            }
        }

        return $floors;
    }
}
