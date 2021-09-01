<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Sector;
use App\Models\Desk;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ListController extends ApiController
{
    /**
     * A list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listFloor()
    {
        $floors = Floor::all();
        // $floors->count = $this->countDataFloor($floors);

        // return response()->json(['data' => $floors]);
        return $this->sendResponse($floors, '');
    }

    /**
     * A list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listSector($floor_id)
    {
        // return $floor_id;
        $sectors = Sector::where('floor_id', $floor_id)->get();
        // $sectors->count = $this->countDataBasedGroup($sectors);

        // return response()->json(['data' => $sectors]);
        return $this->sendResponse($sectors, '');
    }

    /**
     * A list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listDesk($floor_id)
    {
        $desks = Desk::whereHas('sector', function ($sector) use ($floor_id) {
            $sector->where('floor_id', $floor_id);
        })->get();
        // $desks->count = $this->countDataBasedGroup($desks);

        // return response()->json(['data' => $desks]);

        // will renew with booking logic
        foreach ($desks as $desk) {
            $desk->is_available = Arr::random([0, 1]);
        }

        return $this->sendResponse($desks, '');
    }
}
