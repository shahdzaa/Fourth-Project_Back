<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateForm1Request;
use App\Http\Resources\BuildingResource;
use App\Models\Building;
use App\Models\DamageReport;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = Building::all();
        return response()->json([
            'data' => BuildingResource::collection($buildings),
        ]);
    }

}
