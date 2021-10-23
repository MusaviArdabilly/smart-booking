<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sector;

class SectorController extends ApiController
{
    /**
     * A list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($floor_id)
    {
        $sectors = Sector::where('floor_id', $floor_id)->get();

        foreach ($sectors as $sector) {
            $media = $sector->getMedia();
            $url = [];
            try {
                foreach ($sector->media as $item) {
                    if ($item->hasGeneratedConversion('thumb')) {
                        $url[] = $item->getUrl('thumb');
                    } else {
                        $url[] = $item->getUrl();
                    }
                }
                if (!empty($url)) {
                    $sector->media_url = $url;
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
            unset($sector->media);
        }

        return $this->sendResponse('Sectors listed succesfully', $sectors);
    }
}
