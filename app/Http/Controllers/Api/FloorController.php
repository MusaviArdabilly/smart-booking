<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Floor;

class FloorController extends ApiController
{
    /**
     * A list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $floors = Floor::all();
        // $floors->count = $this->countDataFloor($floors);

        foreach ($floors as $floor) {
            $media = $floor->getMedia();
            try {
                $floor->media_url = $floor->media[0]->getUrl();
            } catch (\Throwable $th) {
                //throw $th;
            }
            unset($floor->media);
        }

        return $this->sendResponse('Floors listed succesfully', $floors);
    }
}
