<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Floor;
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
        $floor = Floor::find($floor_id);
        $media = $floor->getMedia();
        return view('admin.sector.index', compact('floor'));
    }

    /**
     * A list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list($floor_id)
    {
        $sectors = Sector::where('floor_id', $floor_id)->get();
        $sectors->count = $this->countDataSector($sectors);

        return response()->json(['data' => $sectors, 'floor_id' => $floor_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($floor_id)
    {
        $floor = Floor::find($floor_id);
        return view('admin.sector.create', compact('floor'));
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
            'description'   => ['nullable', 'string', 'max:255'],
        ]);

        // create new sector
        $sector = Sector::create([
            'floor_id'      => $request->floor_id,
            'name'          => $request->name,
            'description'   => $request->description,
        ]);

        if ($request->hasFile('file')) {
            $files = request()->allFiles()['file'];
            $fileAdders = $sector->addMultipleMediaFromRequest(['file'])
                ->each(function ($fileAdder, $i) use ($files) {
                    $fileAdder->preservingOriginal()
                        ->usingFileName($files[$i++]->hashName())
                        ->toMediaCollection('sectors');
                });
        }

        return redirect()->route('floor.sector.index', $request->floor_id)
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
        // return redirect()->route('desk.index')
        //     ->with(compact('sector'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function edit($floor_id, Sector $sector)
    {
        $floor = Floor::find($floor_id);
        $media = $sector->getMedia();

        return view('admin.sector.edit')->with(compact('sector', 'floor'));
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
            ->with('success', 'Sector deleted successfully.');
    }

    /**
     * @todo count desks based sector id
     * @param Array $sectors
     */
    private function countDataSector($sectors)
    {
        //count sectors and desks based sector id
        foreach ($sectors as $sector) {
            //count sector
            $sector->number_of_desks = $sector->desks()->count();
        }

        return $sectors;
    }
}
